<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Subjects;
use App\Models\Teachers;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Gate::allows('viewTeacher' , Auth::user())) {
            $teacherId = Auth::user()->teacher->id;
            $courses = Courses::where('teacher_id', $teacherId)->get();
        }
        elseif (Gate::allows('viewStudent' , Auth::user())) {
            $studentId = Auth::user()->student->id;
            $courses = Courses::whereHas('enrollments', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })->get();
        }
        elseif (Gate::allows('viewParent' , Auth::user())) {
            $parentId = Auth::user()->id;
            $courses = Courses::whereHas('enrollments.student', function ($query) use ($parentId) {
                $query->where('parent_id', $parentId);
            })->get();
        }
        else {
            $courses = Courses::all();
        }

        return view('pages.courses.list', compact('courses'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subjects = Subjects::all();
        $teachers = Teachers::all();
        return view('pages.courses.create', compact('subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        Courses::create([
            'name' => $request->name,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
        ]);

        Flasher::addSuccess("Successfully created a new course.");

        return response()->json([
            'success' => true,
            'redirect_url' => route('course.index')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function show(Courses $courses)
    {
        return view('pages.courses.show', compact('courses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function edit(Courses $course)
    {
        $subjects = Subjects::all();
        $teachers = Teachers::all();
        return view('pages.courses.edit', compact('course', 'subjects', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Courses $course)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $course->update([
            'name' => $request->name,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
        ]);

        Flasher::addSuccess("Successfully updated the course.");

        return response()->json([
            'success' => true,
            'redirect_url' => route('course.index')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courses $courses)
    {
        $courses->delete();
        Flasher::addSuccess("Course deleted successfully.");

        return response()->json([
            'success' => true,
            'redirect_url' => route('course.index')
        ], 200);
    }
}
