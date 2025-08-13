@extends('layout')

@section('title', 'Add New User')

@section('content')
<h2>Add New User</h2>

<nav>
    <a href="{{ route('users.index') }}">Back to Users List</a>
</nav>

<form method="POST" action="{{ route('users.store') }}">
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
        <button type="submit">Create User</button>
        <a href="{{ route('users.index') }}">Cancel</a>
    </div>
</form>
@endsection
