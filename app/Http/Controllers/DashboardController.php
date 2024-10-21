<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Fees;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Parents;
use App\Models\Salaries;
use App\Models\Students;
use App\Models\teachers;
use App\Models\Enrollments;
use App\Models\Grades;
use App\Models\Subjects;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function AdminDashboard() {
        $studentsCount = Students::count();
        $teachersCount = Teachers::count();
        $parentsCount = Parents::count();
        $coursesCount = Classes::count();

        $totalFees = Fees::sum('amount');
        $totalSalary = Salaries::sum('amount');
        $totalSalaryPending = Salaries::where('status', 'pending')->sum('amount');

        $students = Students::latest()->take(4)->get();
        $teachers = Teachers::latest()->take(4)->get();

        return view('pages/dashboard/dashboard-admin', compact('students','teachers', 'studentsCount', 'teachersCount', 'parentsCount', 'coursesCount', 'totalFees'));
    }




    public function StudentDashboard() {
        $studentId = auth()->user()->student->id;

        $student = Students::with(['class', 'fees', 'attendance', 'grades.subject'])
                           ->findOrFail($studentId);

        $totalFees = $student->fees->sum('amount');
        $paidFees = $student->fees->where('status', 'paid')->sum('amount');
        $pendingFees = $totalFees - $paidFees;

        return view('pages/dashboard/dashboard-student', compact('student', 'totalFees', 'paidFees', 'pendingFees'));
    }




    public function TeacherDashboard() {
        $teacher = Auth::user()->teacher;

        $studentsCount = Students::where('class_id', $teacher->class_id)->count();
        $classesCount = Classes::where('id', $teacher->class_id)->count();
        $subjectsCount = Subjects::where('id', $teacher->subject_id)->count();

        $students = Students::where('class_id', $teacher->class_id)->get();

        $totalSalary = Salaries::where('teacher_id', $teacher->id)->sum('amount');
        $totalSalaryPending = Salaries::where('teacher_id', $teacher->id)->where('status', 'pending')->sum('amount');

        return view('pages/dashboard/dashboard-teacher', compact('students', 'studentsCount', 'classesCount', 'subjectsCount', 'totalSalary', 'totalSalaryPending'));
    }



    public function ParentDashboard() {
        $parentId = auth()->user()->parent->id;
        $children = Students::where('parent_id', $parentId)->get();
        $childrenGrades = Grades::whereIn('student_id', $children->pluck('id'))->get();

        return view('pages/dashboard/dashboard-parent', compact('children', 'childrenGrades'));
    }


}
