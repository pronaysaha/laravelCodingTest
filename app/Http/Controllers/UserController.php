<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

public function store(Request $request)
{
    // Validate the incoming request data
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'account_type' => 'required|in:Individual,Business'
    ]);

    // Encrypt the password before saving
    $data['password'] = Hash::make($data['password']);

    // Create the user
    $user = User::create($data);

    return response()->json(['message' => 'User created successfully!', 'user' => $user], 201);
}

}
