<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Fees;
use App\Models\Parents;
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
        return view('pages/dashboard/dashboard-teacher');
    }

    public function ParentDashboard() {
        return view('pages/dashboard/dashboard-parent');
    }

}
