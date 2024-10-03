<?php

namespace App\Http\Controllers;

use App\Models\Fees;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FeesController extends Controller
{
    /**
     * Display a listing of the fees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fees = Fees::with('student.user')->get();

        if (Gate::allows('viewParent', Auth::user())) {
            $parentId = Auth::user()->parent->id;
            $fees = Fees::whereHas('student.user', function ($query) use ($parentId) {
                $query->where('parent_id', $parentId);
            })->get();
        } elseif (Gate::allows('viewStudent', Auth::user())) {
            $studentId = Auth::user()->student->id;
            $fees = Fees::whereHas('student.user', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })->get();
        }

        return view('pages.fees.list', compact('fees'));
    }

    /**
     * Show the form for creating a new fee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Students::all();
        return view('pages.fees.create', compact('students'));
    }

    /**
     * Store a newly created fee in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        Fees::create([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('fees.index')]);
    }

    /**
     * Show the form for editing the specified fee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fee = Fees::findOrFail($id);
        $students = Students::all();
        return view('pages.fees.edit', compact('fee', 'students'));
    }

    /**
     * Update the specified fee in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $fee = Fees::findOrFail($id);
        $fee->update([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('fees.index')]);
    }

    /**
     * Remove the specified fee from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fee = Fees::findOrFail($id);
        $fee->delete();
        return response()->json(['success' => true, 'message' => 'Fee deleted successfully!']);
    }

    /**
     * Display the specified fee details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fee = Fees::with('student.user')->findOrFail($id);
        return view('fees.show', compact('fee'));
    }
}
