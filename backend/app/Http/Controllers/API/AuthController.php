<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\LoginRequest;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }


    // User Registration API
    public function register(UserCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            return response()->json($this->service->register($validated), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    // User Login API
    public function login(LoginRequest $request)
    {

        $validated = $request->validated();

        try {
            return response()->json($this->service->login($validated), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    // User Profile API (Protected)
    public function profile(Request $request)
    {
        $user = $request->user()->makeHidden('roles');
        //$result = ['user' => $request->user()];
        //$result ['user'][] =  'roles' => $request->user()->roles->pluck('name')
        return response()->json(
            [
            //'success' => true,
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('name')
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
