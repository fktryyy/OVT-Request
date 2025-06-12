<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    // Constructor untuk memastikan user login terlebih dahulu
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan form pengajuan lembur
    public function create(Request $request)
    {
        $employeeResponse = Http::get('http://localhost:3000/employee');
        $employees = $employeeResponse->successful() ? $employeeResponse->json() : [];

        $overtimeData = [];
        if ($request->has(['tanggal_awal', 'tanggal_akhir'])) {
            $overtimeResponse = Http::get('http://localhost:3000/overtime', [
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]);

            if ($overtimeResponse->successful()) {
                $overtimeData = $overtimeResponse->json();
            }
        }

        return view('overtime.request', compact('employees', 'overtimeData'));
    }

    // Mendapatkan data lembur berdasarkan state
    public function filterByState(Request $request)
    {
        $state = $request->input('state', 'f_approve');

        $response = Http::get("http://localhost:3000/overtime", [
            'state' => $state,
        ]);

        if ($response->successful()) {
            $overtimes = $response->json();
            return view('overtime.index', compact('overtimes', 'state'));
        } else {
            return redirect()->back()->with('error', 'Gagal mengambil data lembur berdasarkan status.');
        }
    }

    // Menyimpan pengajuan lembur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|string',
            'date_to' => 'required|string',
            'employee_id' => 'required|string',
            'department_id' => 'required|string',
            'job_id' => 'nullable|string',
            'manager_id' => 'nullable|string',
            'duration_type' => 'required|string',
            'contract_id' => 'nullable|string',
            'type' => 'required|string',
            'attchd_copy' => 'nullable|string',
            'overtime_author' => 'nullable|string', // optional input
        ]);
    
        $user = Auth::user(); // ambil user login
        $defaultAuthor = $user->employee_id;
    
        // Pisahkan input string menjadi array
        $dateFroms = explode(',', $validated['date_from']);
        $dateTos = explode(',', $validated['date_to']);
        $employeeIds = explode(',', $validated['employee_id']);
        $departmentIds = explode(',', $validated['department_id']);
        $jobIds = isset($validated['job_id']) ? explode(',', $validated['job_id']) : [];
        $managerIds = isset($validated['manager_id']) ? explode(',', $validated['manager_id']) : [];
        $durationTypes = explode(',', $validated['duration_type']);
        $contracts = isset($validated['contract_id']) ? explode(',', $validated['contract_id']) : [];
        $types = explode(',', $validated['type']);
        $attchdCopies = isset($validated['attchd_copy']) ? explode(',', $validated['attchd_copy']) : [];
        $overtimeAuthors = isset($validated['overtime_author']) ? explode(',', $validated['overtime_author']) : [];
    
        $count = count($dateFroms);
        if (
            $count === 0 ||
            $count !== count($dateTos) ||
            $count !== count($employeeIds) ||
            $count !== count($departmentIds) ||
            $count !== count($durationTypes) ||
            $count !== count($types)
        ) {
            return redirect()->back()->with('error', 'Data input tidak konsisten atau kosong.');
        }
    
        $responses = [];
    
        for ($i = 0; $i < $count; $i++) {
            try {
                $dateFromUTC = Carbon::parse($dateFroms[$i], 'Asia/Jakarta')->setTimezone('UTC')->format('Y-m-d H:i:s');
                $dateToUTC = Carbon::parse($dateTos[$i], 'Asia/Jakarta')->setTimezone('UTC')->format('Y-m-d H:i:s');
    
                $data = [
                    'employee_id' => (int) $employeeIds[$i],
                    'department_id' => (int) $departmentIds[$i],
                    'job_id' => isset($jobIds[$i]) && $jobIds[$i] !== '' ? (int) $jobIds[$i] : false,
                    'manager_id' => isset($managerIds[$i]) && $managerIds[$i] !== '' ? (int) $managerIds[$i] : false,
                    'duration_type' => $durationTypes[$i],
                    'date_from' => $dateFromUTC,
                    'date_to' => $dateToUTC,
                    'contract_id' => isset($contracts[$i]) && $contracts[$i] !== '' ? (int) $contracts[$i] : false,
                    'attchd_copy' => isset($attchdCopies[$i]) && $attchdCopies[$i] !== '' ? $attchdCopies[$i] : false,
                    'type' => $types[$i],
                    'overtime_author' => isset($overtimeAuthors[$i]) && $overtimeAuthors[$i] !== ''
                        ? (int) $overtimeAuthors[$i]
                        : $defaultAuthor,
                ];
    
                $response = Http::post('http://localhost:3000/overtime/request', $data);
                $responses[] = $response;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error pada baris ' . ($i + 1) . ': ' . $e->getMessage());
            }
        }
    
        if (collect($responses)->every(fn($r) => $r->successful())) {
            return redirect()->back()->with('success', 'Semua data lembur berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Ada data lembur yang gagal disimpan.');
        }
    }
    
    // Menampilkan resume lembur untuk bulan ini
    public function resume()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $response = Http::get("http://localhost:3000/overtime/resume?month={$month}&year={$year}");
        $overtimeData = $response->successful() ? $response->json() : [];
        return view('overtime.resume', compact('overtimeData', 'month', 'year'));
    }

    // Menampilkan halaman dashboard home
    public function dashboard()
    {
        $summary = session('summary', ['total' => 0, 'approved' => 0, 'waiting' => 0]);
        return view('home', compact('summary'));
    }

    // Mendapatkan semua data lembur
    public function getAllOvertime()
    {
        $response = Http::get('http://localhost:3000/overtime');
        return view('overtime.index', ['overtimes' => $response->json()]);
    }

    // Mendapatkan data lembur berdasarkan tanggal tertentu
    public function filterByDate(Request $request)
    {
        $tanggal = $request->input('tanggal');
        if (!$this->isValidDate($tanggal)) {
            return redirect()->back()->with('error', 'Format tanggal tidak valid.');
        }

        $response = Http::get("http://localhost:3000/overtime?tanggal={$tanggal}");
        return view('overtime.index', ['overtimes' => $response->json()]);
    }

    // Mendapatkan data lembur berdasarkan rentang tanggal
    public function filterByRange(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        if (!$this->isValidDate($tanggal_awal) || !$this->isValidDate($tanggal_akhir)) {
            return redirect()->back()->with('error', 'Format tanggal tidak valid.');
        }

        $response = Http::get("http://localhost:3000/overtime?tanggal_awal={$tanggal_awal}&tanggal_akhir={$tanggal_akhir}");
        return view('overtime.index', ['overtimes' => $response->json()]);
    }

    // Tambahan dari kode pertama: fungsi data berdasarkan user login
    public function data(Request $request)
    {
        $validated = $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'status' => 'nullable|string|in:draft,f_approve,approved,refused,all',
        ]);
    
        $user = Auth::user();
    
        $queryParams = [];
    
        // Batasi data berdasarkan employee_id untuk semua user (admin juga tidak bisa lihat semua)
        $queryParams['employee_id'] = $user->employee_id;
    
        if ($request->filled('tanggal_awal')) {
            $queryParams['tanggal_awal'] = $request->tanggal_awal;
        }
    
        if ($request->filled('tanggal_akhir')) {
            $queryParams['tanggal_akhir'] = $request->tanggal_akhir;
        }
    
        if ($request->filled('status') && $request->status !== 'all') {
            $queryParams['state'] = $request->status;
        }
    
        $overtimeData = [];
        $response = Http::get('http://localhost:3000/overtime', $queryParams);
    
        if ($response->successful()) {
            $overtimeData = $response->json();
        }
    
        return view('overtime.data', compact('overtimeData'));
    }
    
    // Tambahan dari kode pertama: menampilkan detail lembur
    public function show($id)
    {
        $response = Http::get("http://localhost:3000/overtime/{$id}");
    
        if ($response->successful()) {
            $overtime = $response->json();
    
            // Cek berdasarkan employee_id, bukan user_id
            if ($overtime['employee_id'] !== Auth::user()->employee_id) {
                abort(403, 'Akses ditolak.');
            }
    
            return view('overtime.show', compact('overtime'));
        }
    
        abort(404, 'Data tidak ditemukan.');
    }
    
    // Validasi tanggal
    private function isValidDate($date)
    {
        return strtotime($date) !== false;
    }

    // Tambahan dari kode pertama: method index untuk form input lembur
    public function index()
    {
        return view('overtime.index');
    }
}