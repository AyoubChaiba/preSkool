<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parents;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Models\Students;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ParentsController extends Controller
{
    public function index() {
        $parents = Parents::with('user')->get();
        return view('pages.parents.list', compact('parents'));
    }

    public function create()
    {
        return view('pages.parents.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            foreach ($validator->errors()->all() as $error) {
                Flasher::error($error);
            }

            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'parent',
        ]);

        if ($user->role === 'parent') {
            Parents::create([
                'user_id' => $user->id,
            ]);
        }

        event(new UserCreated($user, $password, $user->role));

        Flasher::addSuccess($user->name . ' has been created successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('parent.index')
        ], 201);
    }

    public function getChildern() {
        if (Gate::allows('viewParent', Auth::user())) {
            $children = Students::where('parent_id', Auth::user()->parent->id)->get();
        }
        return view('pages.parents.children', compact('children'));
    }


    public function edit($id)
    {
        $parent = Parents::with('user')->findOrFail($id);

        return view('pages.parents.edit', compact('parent'));
    }

    public function update(Request $request, $id)
    {
        $parent = Parents::findOrFail($id);
        $user = $parent->user;

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            Flasher::error('Please fix the following errors:');
            foreach ($validator->errors()->all() as $error) {
                Flasher::error($error);
            }

            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        event(new UserUpdated($user, $request->password, $request->role));

        Flasher::addSuccess($user->name . ' has been updated successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('parent.index')
        ], 200);
    }

    public function destroy($id)
    {
        $parent = Parents::findOrFail($id);

        $parent->user()->delete();

        $parent->delete();

        return response()->json([
            'success' => true,
            'redirect_url' => route('parent.index'),
        ], 200);
    }


}
