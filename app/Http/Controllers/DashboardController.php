<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Enrollments;
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
        $studentId = auth()->user()->id;
        $student = Students::where('user_id', $studentId)->first();
        $coursesCount = Enrollments::where('student_id', $student->id)->count();
        $totalFees = Fees::where('student_id', $student->id)->where('status', 'paid')->sum('amount');
        $pendingFees = Fees::where('student_id', $student->id)->where('status', 'pending')->sum('amount');

        return view('pages/dashboard/dashboard-student', compact('coursesCount', 'totalFees', 'pendingFees'));
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
        $parentId = auth()->user()->id;
        $parent = Parents::where('user_id', $parentId)->first();
        $childrenCount = Students::where('parent_id', $parent->id)->count();
        $totalFees = Parents::where('id', $parent)->where('status', 'paid')->sum('amount');
        $pendingFees = Fees::where('parent_id', $parent->id)->where('status', 'pending')->sum('amount');

        return view('pages/dashboard/dashboard-parent', compact('childrenCount', 'totalFees', 'pendingFees'));
    }

}
