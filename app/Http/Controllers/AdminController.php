<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('pages.admin.list', compact('admins'));
    }


    public function create()
    {
        return view('pages.admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
        ]);

        event(new UserCreated($user, $request->password, $user->role));

        Flasher::addSuccess($user->name . ' has been created successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.index')
        ], 201);
    }



    public function show($id)
    {
        $admin = User::findOrFail($id);
        return view('pages.admin.show', compact('admin'));
    }


    public function edit($id)
    {
        $admin = User::findOrFail($id);

        return view('pages.admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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
            'role' => 'admin',
        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        event(new UserUpdated($user, $request->password, $request->role));

        Flasher::addSuccess($user->name . ' has been updated successfully! Role: ' . $user->role);

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.index')
        ], 200);
    }



    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'success' => true,
            'redirect_url' => route('user.index'),
        ], 200);
    }
}
