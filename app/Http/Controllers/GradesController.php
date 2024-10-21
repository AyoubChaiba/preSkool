<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Exams;
use App\Models\gradePoints;
use App\Models\Grades;
use App\Models\Students;
use App\Models\Subjects;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function index($id)
    {
        $class = Classes::findOrFail($id);
        $exams = Exams::where('class_id', $id)->get();
        $subjects = Subjects::where('class_id', $id)->get();

        return view('pages.grades.index', compact('class', 'exams', 'subjects'));
    }

    public function fetchStudents(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $students = Students::where('class_id', $request->class_id)->get();

        $grades = Grades::where('exam_id', $request->exam_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('class_id', $request->class_id)
                        ->get()
                        ->keyBy('student_id');

        $subject = Subjects::find($request->subject_id);
        $class = Classes::find($request->class_id);
        $exam = Exams::find($request->exam_id);

        if (!$subject || !$class) {
            return response()->json(['error' => 'Subject or Class not found'], 404);
        }

        return response()->json([
            'students' => $students,
            'grades' => $grades,
            'subject' => $subject,
            'class' => $class,
            'exam' => $exam,
            'hasGrades' => $grades->isNotEmpty()
        ]);
    }



    public function saveGrades(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'numeric|min:0|max:100',
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        foreach ($request->grades as $studentId => $grade) {
            Grades::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $request->subject_id,
                    'exam_id' => $request->exam_id,
                    'class_id' => $request->class_id,
                ],
                ['grade' => $grade]
            );
        }

        return response()->json(['message' => 'Grades saved successfully!']);
    }

    public function getStudentGrades($studentId)
    {
        $subjects = Subjects::all();

        $student = Students::with(['grades.subject', 'grades.exam'])
            ->where('id', $studentId)
            ->first();

        $studentGrades = $subjects->map(function ($subject) use ($student) {
            $grades = $student->grades->where('subject_id', $subject->id);
            $totalGrades = $grades->sum('grade');
            $totalExams = $grades->count();
            $averageGrade = $totalExams > 0 ? round($totalGrades / $totalExams, 2) : null;

            $gradePoint = $averageGrade ? gradePoints::where('mark_from', '<=', $averageGrade)
                                                    ->where('mark_upto', '>=', $averageGrade)
                                                    ->first() : null;

            $examDetails = $grades->map(function($grade) {
                return [
                    'exam_name' => $grade->exam->exam_name,
                    'grade' => $grade->grade,
                ];
            });

            return [
                'subject' => $subject->subject_name,
                'total_exams' => $totalExams,
                'total_grade' => $totalGrades,
                'average_grade' => $averageGrade ?? 'N/A',
                'grade_name' => $gradePoint->grade_name ?? 'N/A',
                'comment' => $gradePoint->comment ?? 'No grades yet',
                'exam_details' => $examDetails,
            ];
        });

        return view('pages.grades.student', compact('studentGrades'));
    }


}
