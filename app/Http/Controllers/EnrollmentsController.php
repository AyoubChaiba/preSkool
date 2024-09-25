<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function index() {
        return view('pages.enrollments.list');
    }

    public function store(Request $request) {
        return view('pages.enrollments.create');
    }
}
