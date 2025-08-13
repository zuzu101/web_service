@extends('layout')

@section('title', 'User Details')

@section('content')
<h2>User Details</h2>

<nav>
    <a href="{{ route('users.index') }}">Back to Users List</a> |
    <a href="{{ route('users.edit', $user->user_id) }}">Edit User</a>
</nav>

<h3>User Information:</h3>
<table border="1">
    <tr>
        <th>User ID</th>
        <td>{{ $user->user_id }}</td>
    </tr>
    <tr>
        <th>Username</th>
        <td>{{ $user->username }}</td>
    </tr>
    <tr>
        <th>Full Name</th>
        <td>{{ $user->full_name ?? 'Not set' }}</td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td>{{ $user->phone_number ?? 'Not set' }}</td>
    </tr>
</table>

<br>
<form method="POST" action="{{ route('users.destroy', $user->user_id) }}" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
</form>
@endsection
