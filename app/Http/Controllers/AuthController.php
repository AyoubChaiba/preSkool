<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return response()->json([
                'errors' => ['email' => ['These credentials do not match our records.']]
            ], 401);
        }

        $route = auth()->user()->role . ".dashboard" ;

        return response()->json([
            'success' => true,
            'redirect_url' => route($route)
        ], 200);
    }


    public function register(Request $request) {

        $validator = Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,parent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);

    }

    public function logout() {
        auth()->logout();
        return redirect()->route('view.login');
    }
}
