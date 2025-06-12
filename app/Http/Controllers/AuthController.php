<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function loginView()
    {
        return view('auth.login');
    }

    // Menangani proses login dengan API eksternal
    public function login(Request $request)
    {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $response = Http::post('http://localhost:3000/loginnip', [
        'username' => $request->username,
        'password' => $request->password,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        $employee = $data['employee'] ?? null;

        if (!$employee) {
            return back()->withErrors(['username' => 'Data employee tidak ditemukan dari API']);
        }

        $username = $employee['username'] ?? null;
        $isAdmin = $employee['is_admin_type'] ?? false;

        if (!$username) {
            return back()->withErrors(['username' => 'Username tidak ditemukan di API']);
        }

        if (!$isAdmin) {
            return back()->withErrors(['username' => 'Anda bukan admin, akses ditolak.']);
        }

        // Simpan user ke DB (update atau buat baru)
        $user = User::updateOrCreate(
            ['username' => $username],
            [
                // 'nip' => $nip,
                'username' => $username,
                'name' => $employee['name'] ?? $username,
                'password' => bcrypt('randompassword'),
                'role' => 'admin',
                'employee_id' => $employee['id'],  // Simpan employee_id di user
            ]
        );

        Auth::login($user);
        $request->session()->regenerate();

        // Simpan employee_id ke session juga jika perlu
        $request->session()->put('employee_id', $employee['id']);

        return redirect()->intended('/home')->with('loginName', $user->name ?? $user->username);
    }

    return back()->withErrors(['username' => 'Login gagal atau akses ditolak']);
    }

    

    // Halaman home
    public function home()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        return view('home', compact('user', 'isAdmin'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Registrasi hanya untuk admin
    public function register(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:users,nip',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
        ]);

        User::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('home');
    }
}
