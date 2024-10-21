<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Parents;
use App\Models\Sections;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index()
    {
        $students = collect();

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'parent') {
                $students = $user->parent->students()->with('user', 'class')->get();
            } else {
                $students = Students::with('user', 'class', 'parent')->get();
            }
        }

        return view('pages.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $classes = Classes::all();
        $parents = Parents::all();

        return view('pages.students.create', compact('classes', 'parents'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'student',
            'password' => Hash::make($validated['password']),
        ]);

        $this->createStudentRecord($user->id, $validated);

        return response()->json(['redirect_url' => route('student.index')], 201);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        $student = Students::with('user')->findOrFail($id);
        $classes = Classes::all();
        $parents = Parents::all();
        $sections = Sections::where('class_id', $student->class_id)->get();

        return view('pages.students.edit', compact('student', 'classes', 'parents', 'sections'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {

        $student = Students::with('user')->findOrFail($id);

        $validated = $this->validateStudent($request, $student->user->id);

        $user = $student->user;
        $user->email = $validated['email'];
        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $student->update($this->prepareStudentData($validated));

        return response()->json(['redirect_url' => route('student.index')], 201);
    }


    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        $student = Students::findOrFail($id);
        $student->user()->delete();
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully.'], 200);
    }

    /**
     * Validate the student request data.
     */
    private function validateStudent(Request $request, $userId = null)
    {
        return $request->validate([
            'email' => "required|email|unique:users,email," . ($userId ?? 'NULL'),
            'username' => "required|string|unique:users,username," . ($userId ?? 'NULL'),
            'phone_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'required|exists:parents,id',
            'section_id' => 'nullable|exists:sections,id',
            'admission_date' => 'required|date_format:d-m-Y',
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
        ]);
    }

    /**
     * Prepare the student data for storage.
     */
    private function prepareStudentData(array $validated)
    {
        $validated['date_of_birth'] = \DateTime::createFromFormat('d-m-Y', $validated['date_of_birth'])->format('Y-m-d');
        $validated['admission_date'] = \DateTime::createFromFormat('d-m-Y', $validated['admission_date'])->format('Y-m-d');

        return [
            'name' => $validated['username'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
            'class_id' => $validated['class_id'],
            'parent_id' => $validated['parent_id'],
            'section_id' => $validated['section_id'],
            'gender' => $validated['gender'],
            'admission_date' => $validated['admission_date'],
        ];
    }

    /**
     * Create a new student record.
     */
    private function createStudentRecord($userId, array $validated)
    {
        Students::create(array_merge($this->prepareStudentData($validated), ['user_id' => $userId]));
    }

    public function getSections($classId)
    {
        $sections = Classes::findOrFail($classId)->sections;

        return response()->json($sections);
    }
}
