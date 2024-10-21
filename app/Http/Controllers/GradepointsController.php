<?php

namespace App\Http\Controllers;

use App\Models\gradePoints;
use Illuminate\Http\Request;

class GradePointsController extends Controller
{
    /**
     * Display a listing of the grade points.
     */
    public function index()
    {
        $gradePoints = GradePoints::all();
        return view('pages.gradepoints.index', compact('gradePoints'));
    }

    /**
     * Show the form for creating a new grade point.
     */
    public function create()
    {
        return view('pages.gradepoints.create');
    }

    /**
     * Store a newly created grade point in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateGradePoint($request);

        GradePoints::create($validated);

        return response()->json(['redirect_url' => route('gradepoints.index')], 201);
    }

    /**
     * Show the form for editing the specified grade point.
     */
    public function edit($id)
    {
        $gradepoint = GradePoints::findOrFail($id);

        return view('pages.gradepoints.edit', compact('gradepoint'));
    }

    /**
     * Update the specified grade point in storage.
     */
    public function update(Request $request, $id)
    {
        $gradePoint = GradePoints::findOrFail($id);
        $validated = $this->validateGradePoint($request);

        $gradePoint->update($validated);

        return response()->json(['redirect_url' => route('gradepoints.index')], 201);
    }

    /**
     * Remove the specified grade point from storage.
     */
    public function destroy($id)
    {
        $gradePoint = GradePoints::findOrFail($id);
        $gradePoint->delete();

        return response()->json(['message' => 'Grade point deleted successfully.'], 200);
    }

    /**
     * Validate the grade point request data.
     */
    private function validateGradePoint(Request $request)
    {
        return $request->validate([
            'mark_from' => 'required|numeric|min:0',
            'mark_upto' => 'required|numeric|gte:mark_from',
            'grade_name' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
        ]);

    }
}
