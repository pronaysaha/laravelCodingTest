<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    // Validate the incoming request
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    // Attempt to authenticate the user
    if (Auth::attempt($credentials)) {
        // Authentication passed
        return response()->json(['message' => 'Logged in successfully!']);
    } else {
        // Authentication failed
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}

}
