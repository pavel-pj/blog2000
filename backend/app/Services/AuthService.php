<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $validated): array
    {



        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
             ]);
        $user->assignRole('User');
        $token = $user->createToken('MyAppToken')->plainTextToken;

        return [
            'success' => true,
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user,
         ];
    }

    public function login(array $validated): array
    {

        if (
            !Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
            ])
        ) {
           // $request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyAppToken')->plainTextToken;



        return
        [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email']
                ],
            'roles' => auth()->user()->getRoleNames()->toArray()
            ];
    }
}
