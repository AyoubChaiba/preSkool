<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function AdminDashboard() {
        return view('pages/dashboard/dashboard-admin');
    }

    public function StudentDashboard() {
        return view('pages/dashboard/dashboard-student');
    }

    public function TeacherDashboard() {
        return view('pages/dashboard/dashboard-teacher');
    }

}
