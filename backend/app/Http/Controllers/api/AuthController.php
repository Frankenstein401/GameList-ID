<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{   
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string',
            'role' => 'string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->authService->register($request->all());
        return response()->json($result, $result['status'] === 'success' ? 201 : 500);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->authService->login($request->username, $request->password);
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout();
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function refresh(Request $request)
    {
        $result = $this->authService->refresh();
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }
}
