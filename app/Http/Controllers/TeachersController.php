<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Students;
use App\Events\UserCreated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Validator;


class TeachersController extends Controller
{
    public function index() {
        $students = Students::all();
        return view('pages.students.list', compact('students'));
    }

    public function create()
    {
        return view('pages.students.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'hire_date' => ['required', 'date'],
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

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'teacher',
        ]);

        if ($user->role === 'teacher') {
            Students::create([
                'user_id' => $user->id,
                'admission_date' => $request->admission_date,
                'parent_id' => $request->parent_id,
            ]);
        }

        event(new UserCreated($user, $password, $request->role));

        Flasher::addSuccess($user->name . ' has been created successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('user.index')
        ], 201);
    }
}
