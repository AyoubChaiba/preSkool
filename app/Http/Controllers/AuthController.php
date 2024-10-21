<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function logout() {
        auth()->logout();
        return redirect()->route('view.login');
    }
}
