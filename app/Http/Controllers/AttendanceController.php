<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function show($id)
    {
        $attendances = Attendance::with('student', 'course')
            ->where('student_id', $id)
            ->get();

        return view('pages.courses.students.attendance.show', compact('attendances'));
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

        $attendance = Attendance::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'data' => $attendance
        ], 201);
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $attendance
        ], 200);
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,excused',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully!',
            'data' => $attendance
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
