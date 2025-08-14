<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WebAuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    /**
     * Handle register
     */
    public function register(Request $request)
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

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Redirect admin to admin dashboard
        if ($user->user_id == 999) { // Admin check
            return redirect()->route('admin.dashboard');
        }
        
        // User dashboard - redirect ke user dashboard
        return redirect()->route('user.dashboard');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    /**
     * Show profile
     */
    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return view('auth.profile');
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => 'nullable|string|max:20',
            'full_name' => 'nullable|string|max:100',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed|required_with:current_password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->phone_number = $request->phone_number;
        $user->full_name = $request->full_name;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
