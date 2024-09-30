<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Students;
use App\Models\Enrollments;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class EnrollmentsController extends Controller
{
    public function create()
    {
        $students = Students::all();
        $courses = Courses::all();

        return view('pages.enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date_format:d-m-Y',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $alreadyEnrolled = Enrollments::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($alreadyEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'The student is already enrolled in this course.',
                'errors' => ['course_id' => ['The student is already enrolled in this course.']],
            ], 422);
        }

        $enrollmentDate = Carbon::createFromFormat('d-m-Y', $request->enrollment_date)->format('Y-m-d');

        Enrollments::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'enrollment_date' => $enrollmentDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment created successfully.',
            'redirect_url' => route('enrollment.index')
        ]);
    }

    public function edit(Enrollments $enrollment) {
        $students = Students::all();
        $courses = Courses::all();

        return view('pages.enrollments.edit', compact('enrollment','students', 'courses'));
    }


    public function update(Request $request, Enrollments $enrollment)
{
    $validator = Validator::make($request->all(), [
        'student_id' => 'required|exists:students,id',
        'course_id' => [
            'required',
            'exists:courses,id',
            function ($attribute, $value, $fail) use ($request, $enrollment) {
                $existingEnrollment = Enrollments::where('student_id', $request->student_id)
                    ->where('course_id', $value)
                    ->where('id', '!=', $enrollment->id)
                    ->first();
                if ($existingEnrollment) {
                    $fail('This student is already enrolled in this course.');
                }
            },
        ],
        'enrollment_date' => 'required|date_format:d-m-Y',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $enrollmentDate = Carbon::createFromFormat('d-m-Y', $request->enrollment_date)->format('Y-m-d');

    $enrollment->update([
        'student_id' => $request->student_id,
        'course_id' => $request->course_id,
        'enrollment_date' => $enrollmentDate,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Enrollment updated successfully.',
        'redirect_url' => route('enrollment.index')
    ]);
}


    public function index()
    {
        $enrollments = Enrollments::with(['student.user', 'course'])->get();

        return view('pages.enrollments.list', compact('enrollments'));
    }

    public function destroy($id)
    {
        $enrollment = Enrollments::findOrFail($id);
        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Enrollment deleted successfully.',
        ]);
    }

    public function getStudent($id)
    {
        $course = Courses::with('enrollments.student.user')->findOrFail($id);

        $students = $course->enrollments;


        return view('pages.courses.students.list', compact('students', 'course'));
    }

}
