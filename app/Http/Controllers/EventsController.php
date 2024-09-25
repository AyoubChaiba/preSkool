<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Events;
use App\Models\teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $events = Events::orderBy('start_time', 'asc')->get();
        return view('pages.events.list', compact('events'));
    }

    public function create()
    {
        $courses = Courses::all();
        $teachers = teachers::all();
        return view('pages.events.create', compact('courses', 'teachers'));
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'course_id' => 'required|exists:courses,id',
        'teacher_id' => 'required|exists:teachers,id',
        'start_time' => 'required|date_format:d-m-Y',
        'end_time' => 'required|date_format:d-m-Y|after:start_time',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()
        ], 400);
    }

    $start_time = Carbon::createFromFormat('d-m-Y', $request->input('start_time'))->startOfDay()->format('Y-m-d H:i:s');
    $end_time = Carbon::createFromFormat('d-m-Y', $request->input('end_time'))->endOfDay()->format('Y-m-d H:i:s');

    Events::create([
        'title' => $request->input('title'),
        'course_id' => $request->input('course_id'),
        'teacher_id' => $request->input('teacher_id'),
        'start_time' => $start_time,
        'end_time' => $end_time,
    ]);

    return response()->json([
        'success' => true,
        'redirect_url' => route('event.index')
    ], 201);
    }


    public function edit(Events $event)
    {
        $courses = Courses::all();
        $teachers = teachers::all();
        return view('pages.events.edit', compact('event', 'courses', 'teachers'));
    }

    public function update(Request $request, Events $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
        ]);

        // تحديث الحدث
        $event->update($request->all());
        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Events $event)
    {
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event deleted successfully!');
    }
}

