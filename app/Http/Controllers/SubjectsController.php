<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    public function index() {
        $subjects = Subjects::withCount(['courses', 'teachers'])->get();
        return view('pages.subjects.list', compact('subjects'));
    }

    public function create()
    {
        return view('pages.subjects.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        Subjects::create([
            'name' => $request->name,
        ]);

        Flasher::addSuccess("Successfully created a new subject");

        return response()->json([
            'success' => true,
            'redirect_url' => route('subject.index')
        ], 201);
    }

    public function edit($id) {
        $subject = Subjects::find($id);
        return view('pages.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id) {
        $subject = Subjects::find($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:255'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $subject->update([
            'name' => $request->name,
        ]);

        Flasher::addSuccess("Subject updated successfully");

        return response()->json([
            'success' => true,
            'redirect_url' => route('subject.index')
        ], 200);
    }

    public function destroy($id) {
        $subject = Subjects::find($id);
        $subject->delete();

        return response()->json([
            'success' => true,
            'redirect_url' => route('subject.index')
        ], 200);
    }
}
