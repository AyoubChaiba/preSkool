<?php

namespace App\Http\Controllers;

use App\Models\Fees;
use App\Models\students;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FeesController extends Controller
{
    /**
     * Display a listing of the fees.
     */
    public function index()
    {
        $fees = Fees::with('student')->get();

        $user = auth()->user();

        if ($user->role === "admin") {
            return view('pages.fees.index', compact('fees'));
        } elseif ($user->role === "student") {
            $student = $user->student;
            $fees = Fees::where('student_id', $student->id)->get();
            return view('pages.fees.index', compact('fees'));
        } elseif ($user->role === "parent") {
            $children = $user->parent->children;
            $fees = Fees::whereIn('student_id', $children->pluck('id'))->get();
            return view('pages.fees.index', compact('fees'));
        } else {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
    }


    /**
     * Show the form for creating a new fee.
     */
    public function create()
    {
        $students = students::all();
        return view('pages.fees.create', compact('students'));
    }

    /**
     * Store a newly created fee in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateFee($request);

        $validated['payment_date'] = Carbon::createFromFormat('d-m-Y', $validated['payment_date'])->format('Y-m-d');

        Fees::create($validated);

        return response()->json(['redirect_url' => route('fees.index', $validated['student_id'])], 201);
    }

    /**
     * Show the form for editing the specified fee.
     */
    public function edit($id)
    {
        $fee = Fees::findOrFail($id);
        $students = students::all();

        return view('pages.fees.edit', compact('fee', 'students'));
    }

    /**
     * Update the specified fee in storage.
     */
    public function update(Request $request, $id)
    {
        $fee = Fees::findOrFail($id);
        $validated = $this->validateFee($request);

        $validated['payment_date'] = Carbon::createFromFormat('d-m-Y', $validated['payment_date'])->format('Y-m-d');

        $fee->update($validated);

        return response()->json(['redirect_url' => route('fees.index', $validated['student_id'])], 201);
    }

    /**
     * Remove the specified fee from storage.
     */
    public function destroy($id)
    {
        $fee = Fees::findOrFail($id);
        $fee->delete();

        return response()->json(['message' => 'Fee deleted successfully.'], 200);
    }

    /**
     * Validate the fee request data.
     */
    private function validateFee(Request $request)
    {
        return $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date_format:d-m-Y',
            'status' => 'required|in:paid,pending',
            'student_id' => 'required|exists:students,id',
        ]);
    }
}
