@extends('layout')

@section('title', 'Login')

@section('content')
<h2>Login</h2>

<form method="POST" action="{{ url('/login') }}">
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
        <button type="submit">Login</button>
    </div>
</form>

<p><a href="{{ route('register') }}">Don't have an account? Register here</a></p>
@endsection
