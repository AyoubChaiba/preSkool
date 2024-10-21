<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Sections;
use App\Models\subjects;
use App\Models\teachers;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TimetableController extends Controller
{
    public function index($id)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $class = Classes::findOrFail($id);
        $timetable = [];
        $user = Auth::user();

        $timetable = $this->getTimetableForUserRole($user, $class, $days);

        return view('pages.timetables.index', compact('days', 'timetable', 'class'));
    }

    private function getTimetableForUserRole($user, $class, $days)
    {
        $timetable = [];
        foreach ($days as $day) {
            $query = Timetable::where('day_of_week', $day)->where('class_id', $class->id)->with(['subject', 'teacher', 'section']);

            if ($user->role === 'teacher') {
                $query->where('teacher_id', $user->teacher->id);
            } elseif ($user->role === 'student') {
                $query->where('section_id', $user->student->section_id);
            } elseif ($user->role === 'parent') {
                $query->whereHas('students', function ($query) use ($user) {
                    $query->where('parent_id', $user->parent->id);
                });
            }

            $timetable[$day] = $query->get();
        }

        return $timetable;
    }

    public function create($id)
    {
        $class = Classes::findOrFail($id);
        return view('pages.timetables.create', [
            'class' => $class,
            'subjects' => $class->subjects,
            'teachers' => $class->teachers,
            'sections' => $class->sections,
        ]);
    }

    public function store(Request $request)
    {
        $this->validateTimetable($request);

        Timetable::create($request->all());

        return response()->json([
            'message' => 'Timetable entry created successfully!',
            'redirect_url' => route('timetables.index', $request->input('class_id')),
        ]);
    }

    public function show(Timetable $timetableEntry)
    {
        return view('pages.timetables.show', compact('timetableEntry'));
    }

    public function edit($id)
    {
        $timetable = Timetable::findOrFail($id);
        $class = Classes::find($timetable->class_id);
        $subjects = Subjects::where('class_id', $timetable->class_id)->get();
        $teachers = Teachers::where('class_id', $timetable->class_id)->get();
        $sections = Sections::where('class_id', $timetable->class_id)->get();

        return view('pages.timetables.edit', compact('timetable', 'subjects', 'teachers', 'class', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);
        $this->validateTimetable($request, $timetable->id);
        $timetable->update($request->all());

        return response()->json([
            'message' => 'Timetable entry updated successfully!',
            'redirect_url' => route('timetables.index', $request->input('class_id')),
        ]);
    }

    public function destroy($id)
    {
        $timetableEntry = Timetable::findOrFail($id);
        $timetableEntry->delete();
        return response()->json([
            'message' => 'Timetable entry deleted successfully!'
        ]);
    }

    private function validateTimetable(Request $request, $ignoreId = null)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'section_id' => 'required|exists:sections,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $this->checkForExistingTimetable($request, $ignoreId);
    }

    private function checkForExistingTimetable(Request $request, $ignoreId = null)
    {
        $query = Timetable::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day_of_week', $request->day_of_week);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $existingTimetable = $query->where(function ($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                  });
        })->exists();

        if ($existingTimetable) {
            throw ValidationException::withMessages([
                'start_time' => 'The selected time slot is already occupied for this section.',
                'end_time' => 'The selected time slot is already occupied for this section.'
            ]);
        }
    }
}
