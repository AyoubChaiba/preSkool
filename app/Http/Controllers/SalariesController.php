<?php

namespace App\Http\Controllers;

use App\Models\Salaries;
use App\Models\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SalariesController extends Controller
{
    /**
     * Display a listing of the salaries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salaries = Salaries::with('teacher.user')->get();

        if(Gate::allows('viewTeacher', Auth::user())) {
            $teacherId = Auth::user()->teacher->id;
            $salaries = Salaries::where('teacher_id', $teacherId)->get();
        }

        return view('pages.salaries.list', compact('salaries'));
    }

    /**
     * Show the form for creating a new salary record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = Teachers::all();
        return view('pages.salaries.create', compact('teachers'));
    }

    /**
     * Store a newly created salary in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending',
        ]);

        Salaries::create([
            'teacher_id' => $request->teacher_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('salary.index')]);
    }

    /**
     * Show the form for editing the specified salary.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salaries::findOrFail($id);
        $teachers = Teachers::all();
        return view('pages.salaries.edit', compact('salary', 'teachers'));
    }

    /**
     * Update the specified salary in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending',
        ]);

        $salary = Salaries::findOrFail($id);
        $salary->update([
            'teacher_id' => $request->teacher_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('salary.index')]);
    }

    /**
     * Remove the specified salary from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salary = Salaries::findOrFail($id);
        $salary->delete();

        return response()->json(['success' => true, 'message' => 'salary deleted successfully!']);
    }

    /**
     * Display the specified salary details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salary = Salaries::with('teacher.user')->findOrFail($id);
        return view('salaries.show', compact('salary'));
    }
}
