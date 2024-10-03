<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentsController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersController;
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

    Route::get('/parent/dashboard', [DashboardController::class , "ParentDashboard"])->name('parent.dashboard');


    Route::get('courses/{id}/students', [EnrollmentsController::class, 'getStudent'])->name('courses.students');

    Route::resource('/subject', SubjectsController::class);
    Route::resource('/course', CoursesController::class);

    // Route::middleware(["role:admin,parent"])->group(function () {
    //     Route::get('/parent/children', [ParentsController::class, "getChildern"])->name("parent.children");
    //     Route::resource('/parent', ParentsController::class);
    // });

    Route::get('/parent/children', [ParentsController::class, "getChildern"])->name("parent.children");
    Route::get('/courses/{id}/child', [EnrollmentsController::class, "getCourses"])->name("courses.child");
    Route::resource('/parent', ParentsController::class);

    Route::resource('fees', FeesController::class);
    Route::resource('salary', SalariesController::class);


    Route::get('/attendance/{student_id}/show/{course_id}', [AttendanceController::class, 'show'])->name("show.attendance");
    Route::get('/attendance/{student_id}/create/{course_id}', [AttendanceController::class, 'create'])->name("create.attendance");
    Route::post('/attendance', [AttendanceController::class, 'store'])->name("store.attendance");
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name("edit.attendance");
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name("update.attendance");


    Route::get('/grade/{student_id}/show/{course_id}', [GradesController::class, 'show'])->name("show.grade");
    Route::get('/grade/{student_id}/create/{course_id}', [GradesController::class, 'create'])->name("create.grade");
    Route::post('/grade', [GradesController::class, 'store'])->name("store.grade");
    Route::get('/grade/{id}/edit', [GradesController::class, 'edit'])->name("edit.grade");
    Route::put('/grade/{id}', [GradesController::class, 'update'])->name("update.grade");


    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');

    Route::get('/messages/{id}', [MessagesController::class, 'show'])->name('messages.show');

    Route::get('/messages/create', [MessagesController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store');

    Route::get('/messages/{id}/edit', [MessagesController::class, 'edit'])->name('messages.edit');
    Route::put('/messages/{id}', [MessagesController::class, 'update'])->name('messages.update');

    Route::delete('/messages/{id}', [MessagesController::class, 'destroy'])->name('messages.destroy');




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


