@extends('layout')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>

<p>Welcome, {{ Auth::user()->full_name ?? Auth::user()->username }}!</p>

<nav>
    <a href="{{ route('profile') }}">My Profile</a> |
    <a href="{{ route('users.index') }}">Manage Users</a> |
    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<h3>User Information:</h3>
<table border="1">
    <tr>
        <th>User ID</th>
        <td>{{ Auth::user()->user_id }}</td>
    </tr>
    <tr>
        <th>Username</th>
        <td>{{ Auth::user()->username }}</td>
    </tr>
    <tr>
        <th>Full Name</th>
        <td>{{ Auth::user()->full_name ?? 'Not set' }}</td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td>{{ Auth::user()->phone_number ?? 'Not set' }}</td>
    </tr>
</table>
@endsection
