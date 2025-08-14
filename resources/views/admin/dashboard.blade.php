@extends('layout')

@section('content')
<h1>Admin Dashboard</h1>
<p>Welcome Admin!</p>

<div style="margin: 20px 0;">
    <a href="{{ route('admin.orders.index') }}" style="background: blue; color: white; padding: 10px; text-decoration: none;">Manage Orders</a>
    <a href="{{ route('admin.orders.create') }}" style="background: green; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">Create New Order</a>
    <a href="{{ route('admin.items.index') }}" style="background: purple; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">Manage Items</a>
    
    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 10px;">
        @csrf
        <button type="submit" style="background: red; color: white; padding: 10px; border: none;">Logout</button>
    </form>
</div>

<div style="display: flex; gap: 20px; margin: 20px 0;">
    <div style="background: #f0f0f0; padding: 20px; width: 200px; text-align: center;">
        <h3>Total Orders</h3>
        <h2>{{ $totalOrders }}</h2>
    </div>
    <div style="background: #f0f0f0; padding: 20px; width: 200px; text-align: center;">
        <h3>Total Users</h3>
        <h2>{{ $totalUsers }}</h2>
    </div>
    <div style="background: #ffffcc; padding: 20px; width: 200px; text-align: center;">
        <h3>Pending Orders</h3>
        <h2>{{ $pendingOrders }}</h2>
    </div>
    <div style="background: #ccffcc; padding: 20px; width: 200px; text-align: center;">
        <h3>Completed Orders</h3>
        <h2>{{ $completedOrders }}</h2>
    </div>
</div>

<h2>Recent Orders</h2>
@if($recentOrders && $recentOrders->count() > 0)
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Customer</th>
                <th style="padding: 10px;">Item</th>
                <th style="padding: 10px;">Employee</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Price</th>
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr>
                <td style="padding: 10px;">{{ $order->transaction_id }}</td>
                <td style="padding: 10px;">{{ $order->user->full_name ?? $order->user->username }}</td>
                <td style="padding: 10px;">{{ $order->item->item_name ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $order->employee->employee_name ?? 'Not Assigned' }}</td>
                <td style="padding: 10px;">{{ $order->transactionStatus->status_name ?? 'Unknown' }}</td>
                <td style="padding: 10px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td style="padding: 10px;">{{ $order->transaction_date }}</td>
                <td style="padding: 10px;">
                    <a href="{{ route('admin.orders.show', $order->transaction_id) }}" style="color: blue;">View</a> |
                    <a href="{{ route('admin.orders.edit', $order->transaction_id) }}" style="color: green;">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No orders yet.</p>
@endif
@endsection
