<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentsController extends Controller
{
 /**
     * Display a listing of the parents.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $parents = Parents::all();
        return view('pages.parents.list', compact('parents'));
    }


    public function create()
    {
        return view('pages.parents.create');
    }

    /**
     * Store a newly created parent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:parents,phone_number',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'parent',
        ]);

        Parents::create([
            'user_id' => $user->id,
            'name' => $validated['username'],
            'phone_number' => $validated['phone_number'],
        ]);

        return response()->json(['redirect_url' => route('parent.index')], 201);
    }


    /**
     * Show the form for editing the specified parent.
     *
     * @param  \App\Models\Parent  $parent
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Parents $parent)
    {

        return view('pages.parents.edit', compact('parent'));
    }

    /**
     * Update the specified parent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parent  $parent
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $parent = Parents::findOrFail($id);
        $user = $parent->user;

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|unique:parents,phone_number,' . $id,
            'password' => 'nullable|string|min:6',
        ]);



        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $request->password ? Hash::make($validated['password']) : $user->password,
        ]);

        $parent->update([
            'phone_number' => $validated['phone_number'],
        ]);

        return response()->json(['redirect_url' => route('parent.index')], 200);
    }

    /**
     * Remove the specified parent from storage.
     *
     * @param  \App\Models\Parent  $parent
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Parents $parent)
    {
        $parent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Parent deleted successfully',
        ], 200);
    }
}
