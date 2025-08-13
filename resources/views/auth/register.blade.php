@extends('layout')

@section('title', 'Register')

@section('content')
<h2>Register</h2>

<form method="POST" action="{{ url('/register') }}">
    @csrf
    
    <div>
        <label>Username:</label><br>
        <input type="text" name="username" value="{{ old('username') }}" required>
    </div>
    
    <div>
        <label>Password:</label><br>
        <input type="password" name="password" required>
    </div>
    
    <div>
        <label>Confirm Password:</label><br>
        <input type="password" name="password_confirmation" required>
    </div>
    
    <div>
        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" value="{{ old('phone_number') }}">
    </div>
    
    <div>
        <label>Full Name:</label><br>
        <input type="text" name="full_name" value="{{ old('full_name') }}">
    </div>
    
    <div>
        <button type="submit">Register</button>
    </div>
</form>

<p><a href="{{ route('login') }}">Already have an account? Login here</a></p>
@endsection
