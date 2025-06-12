<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginHistoryController extends Controller
{
    public function index()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Anda tidak memiliki akses.');
    }

    // Hanya dieksekusi jika admin
    $loginHistory = LoginHistory::with('user')
        ->latest()
        ->get();

    return view('login-history.index', compact('loginHistory'));
}

    // Menghapus semua riwayat login milik user
    public function clear()
    {
        LoginHistory::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('status', 'Semua riwayat login berhasil dihapus.');
    }

    // Menghapus satu riwayat login berdasarkan ID
    public function destroy($id)
    {
        $history = LoginHistory::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $history->user_id !== Auth::id()) {
            abort(403);
        }

        $history->delete();

        return redirect()->back()->with('status', 'Riwayat login berhasil dihapus.');
    }
}
