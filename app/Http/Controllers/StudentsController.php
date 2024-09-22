<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function list() {
        $users = User::all();
        return view('pages.users.list', compact('users'));
    }

}

