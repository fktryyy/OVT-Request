<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginHistory;

class HomeController extends Controller  // Pastikan kelas ini turunan dari Controller
{
    public function index()
    {
        $loginHistory = LoginHistory::with('user')
            ->orderBy('logged_in_at', 'desc')
            ->take(10)
            ->get();

        return view('home', compact('loginHistory'));
    }
}
