<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Events;
use App\Models\teachers;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        // جلب كل الأحداث مرتبة حسب وقت البدء
        $events = Events::orderBy('start_time', 'asc')->get();
        return view('pages.events.list', compact('events'));
    }

    public function create()
    {
        $courses = Courses::all(); // جلب كل الدورات
        $teachers = teachers::all(); // جلب كل المعلمين
        return view('pages.events.create', compact('courses', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
        ]);

        Events::create($request->all());
        return redirect()->route('event.index')->with('success', 'Event created successfully!');
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

