<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradepointsController;
use App\Http\Controllers\ConversationController;


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

    Route::get('/admin/dashboard', [DashboardController::class , "AdminDashboard"])->name('admin.dashboard');
    Route::get('/student/dashboard', [DashboardController::class , "StudentDashboard"])->name('student.dashboard');
    Route::get('/parent/dashboard', [DashboardController::class , "ParentDashboard"])->name('parent.dashboard');
    Route::get('/teacher/dashboard', [DashboardController::class , "TeacherDashboard"])->name('teacher.dashboard');

    Route::resource('class', ClassesController::class);
    Route::resource('section', SectionsController::class);

    Route::get('/subject/{id}/class',[SubjectsController::class, "index"])->name('subject.index');
    Route::get('/subject/{id}/create',[SubjectsController::class, "create"])->name('subject.create');
    Route::post('/subject',[SubjectsController::class, "store"])->name('subject.store');
    Route::get('/subject/{id}',[SubjectsController::class, "edit"])->name('subject.edit');
    Route::put('/subject/{id}',[SubjectsController::class, "update"])->name('subject.update');
    Route::delete('/subject/{id}', [SubjectsController::class, 'destroy'])->name('subject.destroy');


    Route::resource('/parent', ParentsController::class);
    Route::resource('/admin', AdminController::class);

    Route::resource('/teacher', TeachersController::class);
    Route::get('/classes/{class}/subjects', [TeachersController::class, 'getSubjects'])->name('classes.subjects');

    Route::resource('/student', StudentsController::class);
    Route::get('/classes/{class}/section', [StudentsController::class, 'getSections'])->name('classes.section');

    Route::get('/timetables/{id}/class',[TimetableController::class, "index"])->name('timetables.index');
    Route::get('/timetables/{id}/create',[TimetableController::class, "create"])->name('timetables.create');
    Route::post('/timetables',[TimetableController::class, "store"])->name('timetables.store');
    Route::get('/timetables/{id}/edit',[TimetableController::class, "edit"])->name('timetables.edit');
    Route::put('/timetables/{id}',[TimetableController::class, "update"])->name('timetables.update');
    Route::delete('/timetables/{id}', [TimetableController::class, 'destroy'])->name('timetables.destroy');


    Route::get('/attendance/{id}/class',[AttendanceController::class, "index"])->name('attendance.index');
    Route::get('/attendance/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('attendance.fetchStudents');
    Route::post('/attendance/save', [AttendanceController::class, 'saveAttendance'])->name('attendance.save');

    Route::get('/exam/{id}/class',[ExamsController::class, "index"])->name('exam.index');
    Route::get('/exam/create',[ExamsController::class, "create"])->name('exam.create');
    Route::post('/exam',[ExamsController::class, "store"])->name('exam.store');
    Route::get('/exam/{id}/edit',[ExamsController::class, "edit"])->name('exam.edit');
    Route::put('/exam/{id}',[ExamsController::class, "update"])->name('exam.update');
    Route::delete('/exam/{id}', [ExamsController::class, 'destroy'])->name('exam.destroy');
    Route::get('/exam/results', [ExamsController::class, 'getResults'])->name('exam.results');

    Route::get('/grade/{id}/class',[GradesController::class, "index"])->name('grade.index');
    Route::get('/grades/fetchStudents', [GradesController::class, 'fetchStudents'])->name('grades.fetchStudents');
    Route::post('/grades/save', [GradesController::class, 'saveGrades'])->name('grades.save');

    Route::resource('/gradepoints', GradepointsController::class);

    Route::resource('fees', FeesController::class);

    Route::resource('salary', SalariesController::class);


    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('/conversations/{id}/markAsRead', [ConversationController::class, 'markAsRead']);


    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'getMessages'])->name('conversations.messages');

    Route::get('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/conversations/create', [ConversationController::class, 'create'])->name('conversations.create');

    Route::get('/users', [ConversationController::class, 'getUsers'])->name('users.index');

    Route::get('/student/{id}/grades', [GradesController::class, 'getStudentGrades'])->name('student.grades');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

});


