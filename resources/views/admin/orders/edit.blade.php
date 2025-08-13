@extends('layout')

@section('content')
<h1>Edit Order #{{ $order->transaction_id }}</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('admin.orders.index') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Orders</a>
    <a href="{{ route('admin.orders.show', $order->transaction_id) }}" style="background: blue; color: white; padding: 10px; text-decoration: none; margin-left: 10px;">View Order</a>
</div>

<form method="POST" action="{{ route('admin.orders.update', $order->transaction_id) }}" style="max-width: 600px;">
    @csrf
    @method('PUT')
    
    <div style="margin: 15px 0;">
        <label><strong>Customer:</strong></label><br>
        <select name="user_id" style="width: 100%; padding: 8px;" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Service Type:</strong></label><br>
        <select name="service_id" style="width: 100%; padding: 8px;" required>
            @foreach($services as $service)
                <option value="{{ $service->service_id }}" 
                        data-price="{{ $service->price }}"
                        {{ $order->service_id == $service->service_id ? 'selected' : '' }}>
                    {{ $service->service_name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Employee:</strong></label><br>
        <select name="employee_id" style="width: 100%; padding: 8px;" required>
            @foreach($employees as $employee)
                <option value="{{ $employee->employee_id }}" {{ $order->employee_id == $employee->employee_id ? 'selected' : '' }}>
                    {{ $employee->employee_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Item Name:</strong></label><br>
        <input type="text" name="item_name" style="width: 100%; padding: 8px;" 
               value="{{ $order->item->item_name ?? '' }}" required>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Item Type:</strong></label><br>
        <select name="item_type" style="width: 100%; padding: 8px;" required>
            <option value="Hardware" {{ ($order->item->item_type ?? '') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
            <option value="Software" {{ ($order->item->item_type ?? '') == 'Software' ? 'selected' : '' }}>Software</option>
            <option value="Printer" {{ ($order->item->item_type ?? '') == 'Printer' ? 'selected' : '' }}>Printer</option>
            <option value="PC" {{ ($order->item->item_type ?? '') == 'PC' ? 'selected' : '' }}>PC</option>
            <option value="Laptop" {{ ($order->item->item_type ?? '') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
            <option value="Other" {{ ($order->item->item_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Status/Description:</strong></label><br>
        <textarea name="item_status" style="width: 100%; height: 100px; padding: 8px;" 
                  placeholder="Describe the problem or status...">{{ $order->item->status ?? '' }}</textarea>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Location:</strong></label><br>
        <select name="location_id" style="width: 100%; padding: 8px;" required>
            @foreach($locations as $location)
                <option value="{{ $location->office_id }}" {{ $order->office_id == $location->office_id ? 'selected' : '' }}>
                    {{ $location->office_address }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Total Price:</strong></label><br>
        <input type="number" name="total_price" id="total_price" style="width: 100%; padding: 8px;" 
               step="0.01" value="{{ $order->total_price }}" required>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Status:</strong></label><br>
        <select name="status_id" style="width: 100%; padding: 8px;" required>
            @foreach($transactionStatuses as $status)
                <option value="{{ $status->status_id }}" 
                        {{ $order->status_id == $status->status_id ? 'selected' : '' }}>
                    {{ $status->status_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin: 20px 0;">
        <button type="submit" style="background: green; color: white; padding: 15px 30px; border: none; cursor: pointer;">
            Update Order
        </button>
        
        <a href="{{ route('admin.orders.show', $order->transaction_id) }}" 
           style="background: gray; color: white; padding: 15px 30px; text-decoration: none; margin-left: 10px;">
            Cancel
        </a>
    </div>
</form>

<script>
// Auto-fill price when service is selected
document.querySelector('select[name="service_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('total_price').value = price;
    }
});
</script>
@endsection
