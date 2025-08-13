@extends('layout')

@section('title', 'Edit User')

@section('content')
<h2>Edit User</h2>

<nav>
    <a href="{{ route('users.index') }}">Back to Users List</a> |
    <a href="{{ route('users.show', $user->user_id) }}">View User</a>
</nav>

<form method="POST" action="{{ route('users.update', $user->user_id) }}">
    @csrf
    @method('PUT')
    
    <div>
        <label>Username:</label><br>
        <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
    </div>
    
    <div>
        <label>Password (leave blank to keep current):</label><br>
        <input type="password" name="password">
    </div>
    
    <div>
        <label>Confirm Password:</label><br>
        <input type="password" name="password_confirmation">
    </div>
    
    <div>
        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
    </div>
    
    <div>
        <label>Full Name:</label><br>
        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}">
    </div>
    
    <div>
        <button type="submit">Update User</button>
        <a href="{{ route('users.index') }}">Cancel</a>
    </div>
</form>
@endsection
