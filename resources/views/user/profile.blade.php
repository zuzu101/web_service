@extends('layout')

@section('content')
<h1>User Profile</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('user.dashboard') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Dashboard</a>
</div>

@if(session('success'))
    <div style="background: green; color: white; padding: 10px; margin: 10px 0;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: red; color: white; padding: 10px; margin: 10px 0;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('user.profile.update') }}" style="max-width: 500px;">
    @csrf
    
    <div style="margin: 15px 0;">
        <label><strong>Username:</strong></label><br>
        <input type="text" name="username" value="{{ Auth::user()->username }}" style="width: 100%; padding: 8px;" required>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Full Name:</strong></label><br>
        <input type="text" name="full_name" value="{{ Auth::user()->full_name }}" style="width: 100%; padding: 8px;">
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Email:</strong></label><br>
        <input type="email" name="email" value="{{ Auth::user()->email }}" style="width: 100%; padding: 8px;" required>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Phone Number:</strong></label><br>
        <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}" style="width: 100%; padding: 8px;">
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Address:</strong></label><br>
        <textarea name="address" style="width: 100%; height: 80px; padding: 8px;">{{ Auth::user()->address }}</textarea>
    </div>

    <hr style="margin: 20px 0;">

    <h3>Change Password</h3>
    <div style="margin: 15px 0;">
        <label><strong>Current Password:</strong></label><br>
        <input type="password" name="current_password" style="width: 100%; padding: 8px;">
    </div>

    <div style="margin: 15px 0;">
        <label><strong>New Password:</strong></label><br>
        <input type="password" name="password" style="width: 100%; padding: 8px;">
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Confirm New Password:</strong></label><br>
        <input type="password" name="password_confirmation" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" style="background: blue; color: white; padding: 15px 30px; border: none; cursor: pointer;">
        Update Profile
    </button>
</form>
@endsection
