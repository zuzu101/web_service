@extends('layout')

@section('content')
<h1>Manage Orders</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('admin.dashboard') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Dashboard</a>
    <a href="{{ route('admin.orders.create') }}" style="background: green; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">Create New Order</a>
</div>

@if($orders && $orders->count() > 0)
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Customer</th>
                <th style="padding: 10px;">Item</th>
                <th style="padding: 10px;">Type</th>
                <th style="padding: 10px;">Employee</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Price</th>
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Note</th>
                <th style="padding: 10px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td style="padding: 10px;">{{ $order->transaction_id }}</td>
                <td style="padding: 10px;">{{ $order->user->full_name ?? $order->user->username }}</td>
                <td style="padding: 10px;">{{ $order->item->item_name ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $order->item->item_type ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $order->employee->employee_name ?? 'Not Assigned' }}</td>
                <td style="padding: 10px;">
                    @if($order->transactionStatus)
                        <span style="background: {{ $order->transactionStatus->status_name == 'paid' ? 'green' : ($order->transactionStatus->status_name == 'unpaid' ? 'orange' : 'red') }}; color: white; padding: 5px;">
                            {{ $order->transactionStatus->status_name }}
                        </span>
                    @else
                        Unknown
                    @endif
                </td>
                <td style="padding: 10px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td style="padding: 10px;">{{ $order->transaction_date }}</td>
                <td style="padding: 10px;">{{ $order->item->status ?? 'No note' }}</td>
                <td style="padding: 10px;">
                    <a href="{{ route('admin.orders.show', $order->transaction_id) }}" style="color: blue;">View</a> |
                    <a href="{{ route('admin.orders.edit', $order->transaction_id) }}" style="color: green;">Edit</a> |
                    <form method="POST" action="{{ route('admin.orders.destroy', $order->transaction_id) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No orders yet.</p>
@endif
@endsection
