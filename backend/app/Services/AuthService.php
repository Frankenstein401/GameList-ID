<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register($data)
    {
        try {
            DB::beginTransaction();

            $newUser = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            /**
             * @var Tymon\JWTAuth\JWTGuard $auth
             */

            $auth = auth('api');
            $token = $auth->login($newUser);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Register successful',
                'accessToken' => $token
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function login(string $username, string $password)
    {
        try {
            $user = User::where('username', $username)->first();

            // NOTE: Add more error handling just in case username doesn't exist
            if (!$user || !Hash::check($password, $user->password) && $username != $user->username) {
                return [
                    'status' => 'error',
                    'message' => 'Username or password inccorect'
                ];
            }

            /**
             * @var Tymon\JWTAuth\JWTGuard $auth
             */

            $auth = auth('api');
            $token = $auth->attempt(['username' => $username, 'password' => $password]);

            return [
                'status' => 'success',
                'message' => 'Login successful',
                'accessToken' => $token,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function logout()
    {
        try {
            /**
             * @var Tymon\JWTAuth\JWTGuard $auth
             */

            $auth = auth('api');
            $auth->logout();

            return [
                'status' => 'success',
                'message' => 'Logout successful'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function refresh()
    {
        try {
            /**
             * @var Tymon\JWTAuth\JWTGuard $auth
             */

            $auth = auth('api');
            $auth->refresh();

            return [
                'status' => 'success',
                'message' => 'Refresh token successful'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
