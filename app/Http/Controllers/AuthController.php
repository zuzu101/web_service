<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    /**
     * Simple algorithm to generate session token
     */
    private function generateSessionToken($username)
    {
        $timestamp = time();
        $random = bin2hex(random_bytes(16));
        return hash('sha256', $username . $timestamp . $random);
    }

    /**
     * Register a new user (API)
     */
    public function registerApi(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:50|unique:users,username',
                'password' => 'required|string|min:6|max:100',
                'phone_number' => 'nullable|string|max:20',
                'full_name' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Buat user baru
            $user = User::create([
                'username' => $request->username,
                'password' => $request->password, // Auto-hashed by model
                'phone_number' => $request->phone_number,
                'full_name' => $request->full_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'phone_number' => $user->phone_number,
                    'full_name' => $user->full_name,
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user (API)
     */
    public function loginApi(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:50',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Cari user
            $user = User::where('username', $request->username)->first();

            // Cek apakah user ada dan password cocok
            if (!$user || !$user->checkPassword($request->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Generate simple session token
            $sessionToken = $this->generateSessionToken($user->username);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'user_id' => $user->user_id,
                        'username' => $user->username,
                        'phone_number' => $user->phone_number,
                        'full_name' => $user->full_name,
                    ],
                    'session_token' => $sessionToken
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users (API)
     */
    public function getAllUsersApi()
    {
        try {
            $users = User::select('user_id', 'username', 'phone_number', 'full_name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user by ID (API)
     */
    public function getUserByIdApi($id)
    {
        try {
            $user = User::select('user_id', 'username', 'phone_number', 'full_name')
                        ->where('user_id', $id)
                        ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user (API)
     */
    public function updateUserApi(Request $request, $id)
    {
        try {
            $user = User::where('user_id', $id)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|max:50|unique:users,username,' . $id . ',user_id',
                'password' => 'sometimes|string|min:6|max:100',
                'phone_number' => 'nullable|string|max:20',
                'full_name' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Update user
            if ($request->has('username')) {
                $user->username = $request->username;
            }
            if ($request->has('password')) {
                $user->password = $request->password; // Auto-hashed by model
            }
            if ($request->has('phone_number')) {
                $user->phone_number = $request->phone_number;
            }
            if ($request->has('full_name')) {
                $user->full_name = $request->full_name;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'phone_number' => $user->phone_number,
                    'full_name' => $user->full_name,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user (API)
     */
    public function deleteUserApi($id)
    {
        try {
            $user = User::where('user_id', $id)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}
