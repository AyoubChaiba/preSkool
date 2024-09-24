<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\Subjects;
use App\Models\Teachers;
use App\Models\User;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class TeachersController extends Controller
{
    public function index() {
        $teachers = Teachers::withCount('courses')->get();
        return view('pages.teachers.list', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subjects::all();
        return view('pages.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'hire_date' => ['required', 'date_format:d-m-Y'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            foreach ($validator->errors()->all() as $error) {
                Flasher::error($error);
            }

            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $hireDate = Carbon::createFromFormat('d-m-Y', $request->hire_date)->format('Y-m-d');


        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'teacher',
        ]);

        if ($user->role === 'teacher') {
            Teachers::create([
                'user_id' => $user->id,
                'hire_date' => $hireDate,
                'subject_id' => $request->subject_id,
            ]);
        }

        event(new UserCreated($user, $password, $request->role));

        Flasher::addSuccess($user->name . ' has been created successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('teacher.index')
        ], 201);
    }
}
