<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentsController;

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

Route::view('/register', 'pages/auth/register')->name('view.register');
Route::post('/register', [AuthController::class,'register'])->name('auth.register');

Route::prefix('error')->group(function () {
    Route::view('/403', 'errors.403')->name('error.403');
    Route::view('/404', 'errors.404')->name('error.404');
    Route::view('/500', 'errors.500')->name('error.500');
});

Route::view('/', 'pages/auth/login')->name('view.login');
Route::view('/forgotPassword', 'pages/auth/forgotPassword')->name('view.forgotPassword');
Route::post('/login', [AuthController::class,'login'])->name('auth.login');


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class , "AdminDashboard"])->name('admin.dashboard');
        Route::resource('/admin', AdminController::class);
        Route::resource('enrollment', EnrollmentsController::class);
    });

    Route::get('/student/dashboard', [DashboardController::class , "StudentDashboard"])->name('student.dashboard');
    Route::resource('/student', StudentsController::class);

    Route::get('/teacher/dashboard', [DashboardController::class , "TeacherDashboard"])->name('teacher.dashboard');
    Route::resource('/teacher', TeachersController::class);

    Route::get('courses/{id}/students', [EnrollmentsController::class, 'getStudent'])->name('courses.students');

    Route::resource('/subject', SubjectsController::class);
    Route::resource('/course', CoursesController::class);
    Route::resource('/parent', ParentsController::class);


    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name("show.attendance");
    Route::get('/attendance/{id}/create', [AttendanceController::class, 'create'])->name("create.attendance");
    Route::post('/attendance', [AttendanceController::class, 'store'])->name("store.attendance");
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name("edit.attendance");



    // Route::middleware(['role:student,admin,teacher'])->group(function () {
    //     Route::get('/student/dashboard', [DashboardController::class , "StudentDashboard"])->name('student.dashboard');
    //     Route::resource('/student', StudentsController::class);
    // });

    // Route::middleware(['role:teacher,admin'])->group(function () {
    //     Route::get('/teacher/dashboard', [DashboardController::class , "TeacherDashboard"])->name('teacher.dashboard');
    //     Route::resource('/teacher', TeachersController::class);
    //     Route::resource('/subject', SubjectsController::class);
    //     Route::resource('/course', CoursesController::class);
    //     Route::resource('/event', EventsController::class);
    //     Route::get('/event/dataJson', [EventsController::class, 'dataJson'])->name('event.getdata');
    // });

    // Route::middleware(['role:parent,admin'])->group(function () {
    //     Route::resource('/parent', ParentsController::class);
    // });


});


