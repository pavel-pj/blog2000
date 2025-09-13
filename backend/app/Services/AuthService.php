<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 

class AuthService
{
 
    public function register (array $validated): Array
    {
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
             ]);

        $token = $user->createToken('MyAppToken')->plainTextToken;

         return [
            'success' => true,
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user,
         ];

    }
    
}