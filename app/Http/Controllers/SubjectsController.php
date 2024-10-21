<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\subjects;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{

    public function index($id)
    {
        $subjects = Subjects::where('class_id', $id)->get();
        $class = Classes::find($id);
        return view('pages.subjects.index', compact('subjects','class'));
    }

    public function create($id)
    {
        $class = Classes::find($id);
        return view('pages.subjects.create', compact('class'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name',
            'class_id' => 'required|exists:classes,id',
        ]);

        Subjects::create([
            'subject_name' => $request->input('subject_name'),
            'class_id' => $request->input('class_id'),
        ]);

        return response()->json([
            'message' => 'Subject created successfully!',
            'redirect_url' => route('subject.index', $request->input('class_id'))
        ]);
    }

    public function edit($id)
    {
        $subject = Subjects::findOrFail($id);
        $class = $subject->class;
        return view('pages.subjects.edit', compact('subject', 'class'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name,' . $id,
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $subject = Subjects::findOrFail($id);
        $subject->update([
            'subject_name' => $request->input('subject_name'),
            'class_id' => $request->input('class_id'),
        ]);

        return response()->json([
            'message' => 'Subject updated successfully!',
            'redirect_url' => route('subject.index', $request->input('class_id'))
        ]);
    }

    public function destroy($id)
    {
        $subject = Subjects::findOrFail($id);
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully!']);
    }
}
