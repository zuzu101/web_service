<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::select('user_id', 'username', 'phone_number', 'full_name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'full_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            User::create([
                'username' => $request->username,
                'password' => $request->password, // Auto-hashed by model
                'phone_number' => $request->phone_number,
                'full_name' => $request->full_name,
            ]);

            return redirect()->route('users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::select('user_id', 'username', 'phone_number', 'full_name')
                   ->where('user_id', $id)
                   ->first();

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found!');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::where('user_id', $id)->first();

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found!');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in database
     */
    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->first();

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found!');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username,' . $id . ',user_id',
            'password' => 'nullable|string|min:6|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'full_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user->username = $request->username;
            $user->phone_number = $request->phone_number;
            $user->full_name = $request->full_name;

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $user->password = $request->password; // Auto-hashed by model
            }

            $user->save();

            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified user from database
     */
    public function destroy($id)
    {
        try {
            $user = User::where('user_id', $id)->first();

            if (!$user) {
                return redirect()->route('users.index')->with('error', 'User not found!');
            }

            $user->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
