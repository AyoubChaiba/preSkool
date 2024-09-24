<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Students;
use App\Models\Parents;
use App\Events\UserCreated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class StudentsController extends Controller
{
    public function index() {
        $students = Students::all();
        return view('pages.students.list', compact('students'));
    }

    public function create()
    {
        $parents = Parents::all();
        return view('pages.students.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'admission_date' => ['required', 'date_format:d-m-Y'],
            'parent_id' => ['required', 'exists:parents,id'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $admissionDate = Carbon::createFromFormat('d-m-Y', $request->admission_date)->format('Y-m-d');

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'student',
        ]);

        Students::create([
            'user_id' => $user->id,
            'admission_date' => $admissionDate,
            'parent_id' => $request->parent_id,
        ]);

        event(new UserCreated($user, $password, $request->role));

        Flasher::addSuccess($user->name . ' has been created successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('student.index')
        ], 201);
    }

    public function show($id)
    {
        $parent = Parents::with('user')->get();
        return view('pages.parents.show', compact('parent'));
    }

    public function edit($id)
    {
        $student = Students::with('user')->findOrFail($id);

        return view('pages.students.edit', compact('student'));
    }




}

