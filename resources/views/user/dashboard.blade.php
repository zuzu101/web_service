@extends('layout')

@section('content')
<h1>User Dashboard</h1>
<p>Welcome, {{ Auth::user()->full_name ?? Auth::user()->username }}!</p>

<div style="margin: 20px 0;">
    <a href="{{ route('user.order.create') }}" style="background: blue; color: white; padding: 10px; text-decoration: none;">Create New Order</a>
    <a href="{{ route('user.profile') }}" style="background: green; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">Profile</a>
    
    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 10px;">
        @csrf
        <button type="submit" style="background: red; color: white; padding: 10px; border: none;">Logout</button>
    </form>
</div>

<h2>My Orders</h2>
@if(Auth::user()->transactions && Auth::user()->transactions->count() > 0)
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 10px;">Order ID</th>
                <th style="padding: 10px;">Item</th>
                <th style="padding: 10px;">Employee</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Price</th>
                <th style="padding: 10px;">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach(Auth::user()->transactions as $order)
            <tr>
                <td style="padding: 10px;">{{ $order->transaction_id }}</td>
                <td style="padding: 10px;">{{ $order->item->item_name ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $order->employee->employee_name ?? 'Not Assigned' }}</td>
                <td style="padding: 10px;">{{ $order->transactionStatus->status_name ?? 'Unknown' }}</td>
                <td style="padding: 10px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td style="padding: 10px;">{{ $order->transaction_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No orders yet. <a href="{{ route('user.order.create') }}">Create your first order!</a></p>
@endif
@endsection
