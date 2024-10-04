<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Fees;
use App\Models\Parents;
use App\Models\Salaries;
use App\Models\Students;
use App\Models\teachers;

class DashboardController extends Controller
{
    public function AdminDashboard() {
        
        $studentsCount = Students::count();
        $teachersCount = teachers::count();
        $parentsCount = Parents::count();
        $coursesCount = Courses::count();
        $totalFees = Fees::sum('amount');

        return view('pages/dashboard/dashboard-admin', compact('studentsCount', 'teachersCount', 'parentsCount', 'coursesCount','totalFees'));
    }

    public function StudentDashboard() {
        return view('pages/dashboard/dashboard-student');
    }

    public function TeacherDashboard() {
        $teacherId = auth()->user()->id;
        $teacher = Teachers::where('user_id', $teacherId)->first();

        $assignedCourses = Courses::where('teacher_id', $teacher->id)->get();
        $coursesCount = Courses::where('teacher_id', $teacher->id)->count();
        $salary = Salaries::where('teacher_id', $teacher->id)->sum('amount');
        $pendingSalary = Salaries::where('teacher_id', $teacher->id)->where('status', 'pending')->sum('amount');
        $studentsCount = Courses::where('teacher_id', $teacher->id)->count();

        return view('pages/dashboard/dashboard-teacher', compact('assignedCourses', 'salary', 'pendingSalary','studentsCount', 'coursesCount'));
    }

    public function ParentDashboard() {
        return view('pages/dashboard/dashboard-parent');
    }

}
