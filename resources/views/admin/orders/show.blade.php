@extends('layout')

@section('content')
<h1>Order Details</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('admin.orders.index') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Orders</a>
    <a href="{{ route('admin.orders.edit', $order->transaction_id) }}" style="background: blue; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">Edit Order</a>
</div>

<div style="max-width: 600px; border: 1px solid #ccc; padding: 20px;">
    <h2>Order #{{ $order->transaction_id }}</h2>
    
    <table style="width: 100%; margin: 20px 0;">
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Customer:</strong></td>
            <td style="padding: 10px;">{{ $order->user->full_name ?? $order->user->username }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Email:</strong></td>
            <td style="padding: 10px;">{{ $order->user->email }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Phone:</strong></td>
            <td style="padding: 10px;">{{ $order->user->phone_number ?? 'Not provided' }}</td>
        </tr>
    </table>

    <h3>Service Information</h3>
    <table style="width: 100%; margin: 20px 0;">
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Service Type:</strong></td>
            <td style="padding: 10px;">{{ $order->service->service_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Employee:</strong></td>
            <td style="padding: 10px;">{{ $order->employee->employee_name ?? 'Not Assigned' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Location:</strong></td>
            <td style="padding: 10px;">{{ $order->location->office_address ?? 'N/A' }}</td>
        </tr>
    </table>

    <h3>Item Information</h3>
    <table style="width: 100%; margin: 20px 0;">
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Item Name:</strong></td>
            <td style="padding: 10px;">{{ $order->item->item_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Item Type:</strong></td>
            <td style="padding: 10px;">{{ $order->item->item_type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Status/Description:</strong></td>
            <td style="padding: 10px;">{{ $order->item->status ?? 'No description' }}</td>
        </tr>
    </table>

    <h3>Order Details</h3>
    <table style="width: 100%; margin: 20px 0;">
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Order Date:</strong></td>
            <td style="padding: 10px;">{{ $order->transaction_date }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Total Price:</strong></td>
            <td style="padding: 10px;"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f5f5f5;"><strong>Status:</strong></td>
            <td style="padding: 10px;">
                @if($order->transactionStatus)
                    <span style="background: {{ $order->transactionStatus->status_name == 'paid' ? 'green' : ($order->transactionStatus->status_name == 'unpaid' ? 'orange' : 'red') }}; color: white; padding: 5px;">
                        {{ $order->transactionStatus->status_name }}
                    </span>
                @else
                    Unknown
                @endif
            </td>
        </tr>
    </table>

    @if($order->item && $order->item->status)
        <h3>Problem Description</h3>
        <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; margin: 20px 0;">
            {{ $order->item->status }}
        </div>
    @endif
</div>
@endsection
