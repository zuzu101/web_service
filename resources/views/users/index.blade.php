@extends('layout')

@section('title', 'Users Management')

@section('content')
<h2>Users Management</h2>

<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('users.create') }}">Add New User</a>
</nav>

<h3>All Users:</h3>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Phone Number</th>
        <th>Actions</th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->user_id }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->full_name ?? 'Not set' }}</td>
        <td>{{ $user->phone_number ?? 'Not set' }}</td>
        <td>
            <a href="{{ route('users.show', $user->user_id) }}">View</a> |
            <a href="{{ route('users.edit', $user->user_id) }}">Edit</a> |
            <form method="POST" action="{{ route('users.destroy', $user->user_id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@if($users->isEmpty())
<p>No users found.</p>
@endif
@endsection
