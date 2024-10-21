<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\students;
use App\Models\Attendance;
use App\Models\Sections;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index($id)
    {
        $class = Classes::find($id);
        $sections = Sections::where('class_id', $id)->get();
        return view('pages.attendances.index', compact('class', 'sections'));
    }

    public function fetchStudents(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'section_id' => 'required|exists:sections,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $date = $request->input('date');
        $sectionId = $request->input('section_id');
        $classId = $request->input('class_id');

        $students = Students::where('section_id', $sectionId)
            ->where('class_id', $classId)
            ->with(['section', 'class', 'attendance' => function($query) use ($date) {
                $query->where('date', $date);
            }])
            ->orderBy('name', 'asc')
            ->get();

        if ($students->isEmpty()) {
            return response()->json([
                'students' => [],
                'attendance' => [],
                'message' => 'No students found for this section and class.'
            ], 404);
        }

        $attendanceData = $students->map(function($student) {
            $attendanceRecord = $student->attendance->first();

            return [
                'id' => $student->id,
                'name' => $student->name,
                'section' => $student->section->section_name,
                'class' => $student->class->class_name,
                'attendance_status' => $attendanceRecord ? $attendanceRecord->status : null
            ];
        });

        return response()->json([
            'students' => $attendanceData,
            'message' => 'Attendance data retrieved successfully.'
        ]);
    }

    public function saveAttendance(Request $request)
    {
        $request->validate([
            'status' => 'required|array',
            'date' => 'required|date',
            'class_id' => 'required|integer'
        ]);

        $attendanceData = $request->input('status');
        $date = $request->input('date');

        if (empty($attendanceData)) {
            return response()->json(['message' => 'No attendance data provided'], 400);
        }

        foreach ($attendanceData as $student_id => $status) {
            $attendance = Attendance::where('student_id', $student_id)
                                    ->where('date', $date)
                                    ->first();

            if ($attendance) {
                $attendance->status = $status;
                $attendance->save();
            } else {
                Attendance::create([
                    'student_id' => $student_id,
                    'class_id' => $request->input('class_id'),
                    'date' => $date,
                    'status' => $status ,
                ]);
            }
        }

        return response()->json(['message' => 'Attendance saved successfully']);
    }

    public function show($id)
    {
        $student = Students::findOrFail($id);
        $attendances = Attendance::where('student_id', $student->id)->orderBy('date', 'asc')->get();

        return view('pages.attendances.report', compact('student', 'attendances'));
    }

}
