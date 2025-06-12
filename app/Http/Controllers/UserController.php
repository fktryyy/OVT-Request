<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman form registrasi (optional, jika perlu)
    public function create()
    {
        return view('auth.register');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',  // Role hanya bisa 'user' atau 'admin'
        ]);

        // Membuat user baru dan menyimpannya ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,  // Mengisi field role
        ]);

        // Mengalihkan ke halaman home atau ke halaman lain yang sesuai
        return redirect()->route('home')->with('success', 'User registered successfully!');
    }
}
