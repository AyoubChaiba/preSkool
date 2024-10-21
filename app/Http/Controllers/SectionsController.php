<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Sections;
use App\Models\Teachers;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function index()
    {
        $sections = Sections::with(['class', 'teacher'])->get();
        return view('pages.sections.index', compact('sections'));
    }

    public function create()
    {
        $classes = Classes::all();
        $teachers = Teachers::all();
        return view('pages.sections.create', compact('classes','teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'capacity' => 'required|integer',
            'class_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        Sections::create([
            'name' => $request->input('name'),
            'section_name' => $request->input('section_name'),
            'class_id' => $request->input('class_id'),
            'capacity' => $request->input('capacity'),
            'class_teacher_id' => $request->input('class_teacher_id'),
        ]);

        return response()->json(['message' => 'Section created successfully!', 'redirect_url' => route('section.index')]);
    }

    public function edit($id)
    {
        $section = Sections::findOrFail($id);
        $classes = Classes::all();
        $teachers = Teachers::all();
        return view('pages.sections.edit', compact('section', 'classes','teachers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'class_teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer',
        ]);

        $section = Sections::findOrFail($id);
        $section->update([
            'name' => $request->input('name'),
            'section_name' => $request->input('section_name'),
            'class_id' => $request->input('class_id'),
            'class_teacher_id' => $request->input('class_teacher_id'),
            'capacity' => $request->input('capacity'),
        ]);

        return response()->json(['message' => 'Section updated successfully!', 'redirect_url' => route('section.index')]);
    }

    public function destroy($id)
    {
        $section = Sections::findOrFail($id);
        $section->delete();

        return response()->json(['message' => 'Section deleted successfully!']);
    }
}
