@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<h2>My Profile</h2>

<form action="{{ route('profile') }}" method="POST">
    @csrf
    
    <h3>Basic Information</h3>
    
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" value="{{ Auth::user()->username }}" disabled>
        <small>(Username cannot be changed)</small>
    </div>

    <div>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}">
        @error('full_name')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}">
        @error('phone_number')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <h3>Change Password (Optional)</h3>
    
    <div>
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password">
        @error('current_password')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password">
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="password_confirmation">Confirm New Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation">
    </div>

    <div>
        <button type="submit">Update Profile</button>
        <a href="{{ route('dashboard') }}">Cancel</a>
    </div>
</form>
@endsection
