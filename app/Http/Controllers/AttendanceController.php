<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Courses;
use App\Models\Students;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function show($student_id , $course_id)
    {
        $attendances = Attendance::with('student', 'course')
            ->where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->get();

        return view('pages.courses.students.attendance.show', compact('attendances'));
    }



    public function create( $student_id , $course_id )
    {
        $student = Students::find($student_id);
        $course = Courses::find($course_id);


        return view('pages.courses.students.attendance.create', compact('student', 'course'));
    }


    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,excused',
        ]);

        Attendance::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'redirect_url' => route('courses.students' , $request->course_id)
        ], 201);
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);

        return view('pages.courses.students.attendance.edit', compact('attendance'));

    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'attendance_date' => 'required|date',
        'status' => 'required|in:present,absent,excused',
    ]);

    $attendance = Attendance::findOrFail($id);

    $attendance->update($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Attendance updated successfully!',
        'redirect_url' => route('courses.students' , $attendance->course_id)
    ], 200);
}


    /**
     * Remove the specified attendance record from storage.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully!'
        ], 200);
    }
}
