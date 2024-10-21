<?php

namespace App\Http\Controllers;

use App\Models\Salaries;
use App\Models\teachers;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalariesController extends Controller
{
    /**
     * Display a listing of the salaries.
     */
    public function index()
    {
        $salaries = Salaries::with('teacher')->get();
        return view('pages.salaries.index', compact('salaries'));
    }

    /**
     * Show the form for creating a new salary.
     */
    public function create()
    {
        $teachers = Teachers::all();
        return view('pages.salaries.create', compact('teachers'));
    }

    /**
     * Store a newly created salary in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateSalary($request);

        $validated['payment_date'] = Carbon::createFromFormat('d-m-Y', $validated['payment_date'])->format('Y-m-d');

        Salaries::create($validated);

        return response()->json(['redirect_url' => route('salary.index', $validated['teacher_id'])], 201);
    }

    /**
     * Show the form for editing the specified salary.
     */
    public function edit($id)
    {
        $salary = Salaries::findOrFail($id);
        $teachers = Teachers::all();

        return view('pages.salaries.edit', compact('salary', 'teachers'));
    }

    /**
     * Update the specified salary in storage.
     */
    public function update(Request $request, $id)
    {
        $salary = Salaries::findOrFail($id);
        $validated = $this->validateSalary($request);

        $validated['payment_date'] = Carbon::createFromFormat('d-m-Y', $validated['payment_date'])->format('Y-m-d');

        $salary->update($validated);

        return response()->json(['redirect_url' => route('salary.index', $validated['teacher_id'])], 201);
    }

    /**
     * Remove the specified salary from storage.
     */
    public function destroy($id)
    {
        $salary = Salaries::findOrFail($id);
        $salary->delete();

        return response()->json(['message' => 'Salary deleted successfully.'], 200);
    }

    /**
     * Validate the salary request data.
     */
    private function validateSalary(Request $request)
    {
        return $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date_format:d-m-Y',
            'status' => 'required|in:paid,pending',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
    }
}
