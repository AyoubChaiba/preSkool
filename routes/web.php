<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::view('/login', 'pages/auth/login')->name('view.login');
Route::view('/register', 'pages/auth/register')->name('view.register');
Route::view('/forgotPassword', 'pages/auth/forgotPassword')->name('view.forgotPassword');

Route::post('/login', [AuthController::class,'login'])->name('auth.login');
Route::post('/register', [AuthController::class,'register'])->name('auth.register');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware(['role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class , "AdminDashboard"])->name('admin.dashboard');
        Route::resource('/user', UserController::class);
    });
});

Route::middleware(['role:student'])->group(function () {
    Route::get('/student/dashboard', [DashboardController::class , "StudentDashboard"])->name('student.dashboard');
});

Route::middleware(['role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [DashboardController::class , "TeacherDashboard"])->name('teacher.dashboard');
});
