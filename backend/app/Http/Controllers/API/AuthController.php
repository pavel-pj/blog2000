<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use Validator;

class AuthController extends Controller
{
    // User Registration API
    public function register(UserCreateRequest $request)
    {

        $validated = $request->validated();
      
        $user = User::create([

            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
             ]);
        
        $token = $user->createToken('MyAppToken')->plainTextToken;
             
        return response()->json(
            [
            'success' => true,
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user,
            ]
        ); 
       

    }

    // User Login API
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyAppToken')->plainTextToken;

        return response()->json(
            [
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
            ]
        );
    }

    // User Profile API (Protected)
    public function profile(Request $request)
    {
        return response()->json(
            [
            'success' => true,
            'user' => $request->user(),
            ]
        );
    }

    public function abba(Request $request)
    {
        return response()->json(
            [
            'success' => true,
            'user' => $request->user(),
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(
            [
            'success' => true,
            'message' => 'Logout successful.',
            ]
        );
    }
}
