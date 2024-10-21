<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeachersController extends Controller
{
    /**
     * Display a listing of the teachers.
     */
    public function index()
    {
        $teachers = Teachers::with('user')->get();
        return view('pages.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $classes = Classes::all();

        return view('pages.teachers.create', compact('classes'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTeacher($request);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'teacher',
            'password' => Hash::make($validated['password']),
        ]);

        $this->createTeacherRecord($user->id, $validated);

        return response()->json(['redirect_url' => route('teacher.index')], 201);
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit($id)
    {
        $teacher = Teachers::with('user')->findOrFail($id);
        $classes = Classes::all();
        $subjects = Subjects::where('class_id', $teacher->class_id)->get();

        return view('pages.teachers.edit', compact('teacher', 'classes', 'subjects'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teachers::with('user')->findOrFail($id);
        $validated = $this->validateTeacher($request, $teacher->user->id);

        $teacher->update($this->prepareTeacherData($validated));

        if (isset($validated['password']) && !empty($validated['password'])) {
            $teacher->user->update(['password' => Hash::make($validated['password'])]);
        }

        return response()->json(['redirect_url' => route('teacher.index')], 201);
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy($id)
    {
        $teacher = Teachers::findOrFail($id);
        $teacher->user()->delete();
        $teacher->delete();

        return response()->json(['message' => 'Teacher deleted successfully.'], 200);
    }

    /**
     * Validate the teacher request data.
     */
    private function validateTeacher(Request $request, $userId = null)
    {
        return $request->validate([
            'email' => "required|email|unique:users,email," . ($userId ?? 'NULL'),
            'username' => "required|string|unique:users,username," . ($userId ?? 'NULL'),
            'phone_number' => 'required|string|max:15',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'hire_date' => 'required|date',
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
        ]);
    }

    /**
     * Prepare the teacher data for storage.
     */
    private function prepareTeacherData(array $validated)
    {
        $validated['hire_date'] = \DateTime::createFromFormat('d-m-Y', $validated['hire_date'])->format('Y-m-d');

        return [
            'name' => $validated['username'],
            'phone_number' => $validated['phone_number'],
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'hire_date' => $validated['hire_date'],
        ];
    }

    /**
     * Create a new teacher record.
     */
    private function createTeacherRecord($userId, array $validated)
    {
        Teachers::create(array_merge($this->prepareTeacherData($validated), ['user_id' => $userId]));
    }

    public function getSubjects($classId)
    {
        $subjects = Classes::findOrFail($classId)->subjects;

        return response()->json($subjects);
    }

}
