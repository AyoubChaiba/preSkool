<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::withCount(['teachers', 'students', 'sections'])->get();
        return view('pages.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('pages.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'number_name' => 'required|string|max:255',
        ]);

        Classes::create([
            'class_name' => $request->input('class_name'),
            'number_name' => $request->input('number_name')
        ]);

        return response()->json(['message' => 'Class created successfully!', 'redirect_url' => route('class.index')]);
    }

    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        return view('pages.classes.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'number_name' => 'required|string|max:255'
        ]);

        $class = Classes::findOrFail($id);
        $class->update([
            'class_name' => $request->input('class_name'),
            'number_name' => $request->input('number_name'),
            'capacity' => $request->input('capacity'),
        ]);

        return response()->json(['message' => 'Class updated successfully!', 'redirect_url' => route('class.index')]);
    }

    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        return response()->json(['message' => 'Class deleted successfully!']);
    }
}
