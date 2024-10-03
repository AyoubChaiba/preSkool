<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Grades;
use App\Models\Students;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    /**
     * Display a listing of the grades.
     */
    public function show($student_id, $course_id)
    {
        $grades = Grades::with('student', 'course')
            ->where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->get();

        return view('pages.courses.students.grades.show', compact('grades'));
    }

    public function create($student_id, $course_id)
    {
        $student = Students::find($student_id);
        $course = Courses::find($course_id);

        return view('pages.courses.students.grades.create', compact('student', 'course'));
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|numeric|min:0|max:100',
            'grade_date' => 'required|date',
        ]);

        Grades::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Grade recorded successfully!',
            'redirect_url' => route('courses.students', $request->course_id),
        ], 201);
    }

    public function edit($id)
    {
        $grade = Grades::findOrFail($id);
        return view('pages.courses.students.grades.edit', compact('grade'));
    }

    /**
     * Update the specified grade in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grade_date' => 'required|date',
            'grade' => 'required|numeric|min:0|max:100',
        ]);

        $grade = Grades::findOrFail($id);
        $grade->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Grade updated successfully!',
            'redirect_url' => route('courses.students', $grade->course_id),
        ], 200);
    }

    public function destroy($id)
    {
        $grade = Grades::findOrFail($id);
        $grade->delete();

        return response()->json([
            'success' => true,
            'message' => 'Grade deleted successfully!',
        ], 200);
    }
}
