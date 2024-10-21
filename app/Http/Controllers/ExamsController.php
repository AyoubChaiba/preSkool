<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Exams;
use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamsController extends Controller
{
    /**
     * Display a listing of the exams.
     */
    public function index($id)
    {
        $exams = Exams::where('class_id', $id)->with(['class', 'subject'])->get();
        $class = Classes::find($id);
        return view('pages.exams.index', compact('exams', 'class'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $classes = Classes::all();
        return view('pages.exams.create', compact('classes'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateExam($request);


        Exams::create($validated);

        return response()->json(['redirect_url' => route('exam.index',$validated['class_id'])], 201);
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit($id)
    {
        $exam = Exams::findOrFail($id);
        $classes = Classes::all();
        $subjects = Subjects::where('class_id', $exam->class_id)->get();

        return view('pages.exams.edit', compact('exam', 'classes', 'subjects'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, $id)
    {
        $exam = Exams::findOrFail($id);
        $validated = $this->validateExam($request);


        $exam->update($validated);

        return response()->json(['redirect_url' => route('exam.index',$validated['class_id'])], 201);
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy($id)
    {
        $exam = Exams::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully.'], 200);
    }

    /**
     * Validate the exam request data.
     */
    private function validateExam(Request $request)
    {

        $validated = $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $validated['exam_date'] = Carbon::createFromFormat('d-m-Y', $request->input('exam_date'))->format('Y-m-d');

        return $validated;
    }


    public function getResults()
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            $student = Auth::user()->student;

            $grades = $student->grades()->with(['exam', 'subject'])->get();

            return view('pages.students.exams', compact('grades'));
        }

        return response()->json(['error' => 'Unauthorized or not a student'], 403);
    }


}
