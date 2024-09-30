<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Models\Parents;
use App\Models\Students;
use App\Models\User;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentsController extends Controller
{
    public function index() {
        $students = Students::all();
        if (Gate::allows('viewTeacher', Auth::user())) {
            $teacherId = Auth::user()->id;
            $students = Students::whereHas('courses', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->get();
        }
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
        $parents = Parents::all();

        return view('pages.students.edit', compact('student','parents'));
    }

        public function update(Request $request, $id)
    {
        $student = Students::with('user')->findOrFail($id);
        $user = $student->user;

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'admission_date' => ['required', 'date_format:d-m-Y'],
            'parent_id' => ['required', 'exists:parents,id'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $admissionDate = Carbon::createFromFormat('d-m-Y', $request->admission_date)->format('Y-m-d');
        $student->admission_date = $admissionDate;
        $student->parent_id = $request->parent_id;
        $student->save();

        Flasher::addSuccess($user->name . ' has been updated successfully!');

        return response()->json([
            'success' => true,
            'redirect_url' => route('student.index')
        ], 200);
    }


    public function destroy($id)
    {
        $student = Students::with('user')->findOrFail($id);
        $user = $student->user;

        $student->delete();

        $user->delete();

        Flasher::addSuccess($user->name . ' has been deleted successfully!');

        return response()->json([
            'success' => true,
            'redirect_url' => route('student.index')
        ], 200);
    }


}

