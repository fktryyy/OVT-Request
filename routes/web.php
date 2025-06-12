<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\OvertimeByDepartmentController;


// Redirect root ke login jika belum login, atau ke dashboard jika sudah login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


// Login
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout (harus login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register (hanya bisa diakses admin yang sudah login)
Route::get('/register', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('auth.register');
    }
    abort(403);
})->middleware('auth')->name('register');

// Proses simpan registrasi
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Redirect /dashboard ke form lembur
Route::get('/dashboard', fn () => redirect()->route('overtime.form'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Grup route yang butuh autentikasi
Route::middleware(['auth'])->group(function () {

    // Home / Dashboard lembur
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Form pengajuan lembur
    Route::get('/overtime/request', [OvertimeController::class, 'create'])->name('overtime.form');
    Route::post('/overtime/request', [OvertimeController::class, 'store'])->name('overtime.submit');

 

    Route::get('/request-by-department', [OvertimeByDepartmentController::class, 'index'])->name('request.by.department');
    Route::post('/request/store', [OvertimeByDepartmentController::class, 'store'])->name('request.store');

    

    // Data lembur
    Route::get('/overtime/data', [OvertimeController::class, 'data'])->name('overtime.data');

    // Filter lembur
    Route::get('/overtime/filter-date', [OvertimeController::class, 'filterByDate'])->name('overtime.byDate');
    Route::get('/overtime/filter-range', [OvertimeController::class, 'filterByRange'])->name('overtime.byRange');
    Route::get('/overtime/filter-month', [OvertimeController::class, 'filterByMonth'])->name('overtime.byMonth');

    // Riwayat login
    Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
    Route::post('/login-history/clear', [LoginHistoryController::class, 'clear'])->name('login-history.clear');
    Route::delete('/login-history/{id}', [LoginHistoryController::class, 'destroy'])->name('login-history.delete');
    Route::get('/overtime/state', [OvertimeController::class, 'filterByState'])->name('overtime.byState');

    Route::get('/overtime/create', [OvertimeController::class, 'create'])->name('overtime.create');
    // web.php
Route::get('/overtime/batch-get-employees', [OvertimeController::class, 'batchGetEmployees'])->name('overtime.batchGetEmployees');
Route::post('/overtime/batch-generate', [OvertimeController::class, 'batchGenerate'])->name('overtime.batchGenerate');

});
