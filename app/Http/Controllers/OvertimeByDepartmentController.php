<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class OvertimeByDepartmentController extends Controller
{
    public function index()
    {
        // dd('masuk halaman form');
        return view('overtime.request-by-department');
    }

    public function store(Request $request)
    {
        $data = $request->all();
    
        if (!isset($data['employee']) || !is_array($data['employee'])) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan atau tidak valid.');
        }
    
        $user = Auth::user();
        $defaultAuthor = $user->employee_id;
        $masterBatch = $data['master_batch'] ?? null;
        $authorId = $data['overtime_author'] ?? $defaultAuthor;
    
        $responses = [];
    
        foreach ($data['employee'] as $index => $emp) {
            try {
                // validasi minimal
                if (
                    empty($emp['id']) || empty($emp['date_from']) || empty($emp['date_to']) ||
                    empty($emp['type']) || empty($emp['duration_type'])
                ) {
                    continue; // skip jika data tidak lengkap
                }
    
                $dateFromUTC = Carbon::parse($emp['date_from'], 'Asia/Jakarta')->setTimezone('UTC')->format('Y-m-d H:i:s');
                $dateToUTC = Carbon::parse($emp['date_to'], 'Asia/Jakarta')->setTimezone('UTC')->format('Y-m-d H:i:s');
    
                $payload = [
                    'employee_id' => (int) $emp['id'],
                    'department_id' => isset($emp['department_id']) ? (int) $emp['department_id'] : null,
                    'job_id' => isset($emp['job_id']) ? (int) $emp['job_id'] : null,
                    'manager_id' => isset($emp['manager_id']) ? (int) $emp['manager_id'] : null,
                    'duration_type' => $emp['duration_type'],
                    'date_from' => $dateFromUTC,
                    'date_to' => $dateToUTC,
                    'contract_id' => isset($emp['contract_id']) ? (int) $emp['contract_id'] : null,
                    'attchd_copy' => $emp['attchd_copy'] ?? '',
                    'type' => $emp['type'],
                    'overtime_author' => (int) $authorId,
                    'master_batch' => $masterBatch,
                ];
    
                $response = Http::post('http://localhost:3000/overtime/request', $payload);
                $responses[] = $response;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal simpan data baris ' . ($index + 1) . ': ' . $e->getMessage());
            }
        }
    
        if (collect($responses)->every(fn($r) => $r->successful())) {
            return redirect()->back()->with('success', 'Semua data lembur berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Beberapa data lembur gagal disimpan.');
        }
    }
    
}
