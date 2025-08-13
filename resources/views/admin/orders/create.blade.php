@extends('layout')

@section('content')
<h1>Create New Order</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('admin.orders.index') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Orders</a>
</div>

<form method="POST" action="{{ route('admin.orders.store') }}" style="max-width: 600px;">
    @csrf
    
    <div style="margin: 15px 0;">
        <label><strong>Customer:</strong></label><br>
        <select name="user_id" style="width: 100%; padding: 8px;" required>
            <option value="">Select Customer</option>
            @if($users && $users->count() > 0)
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}">
                        {{ $user->full_name ?? $user->username ?? 'Unknown' }} 
                        @if($user->email)
                            ({{ $user->email }})
                        @endif
                    </option>
                @endforeach
            @else
                <option value="">No customers available - Register some users first</option>
            @endif
        </select>
        @if(!$users || $users->count() == 0)
            <small style="color: red;">No customers found. <a href="{{ route('register') }}" target="_blank">Register some users first</a></small>
        @endif
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Service Type:</strong></label><br>
        <select name="service_id" style="width: 100%; padding: 8px;" required>
            <option value="">Select Service</option>
            @if($services && $services->count() > 0)
                @foreach($services as $service)
                    <option value="{{ $service->service_id }}" data-price="{{ $service->service_price ?? 0 }}">
                        {{ $service->service_type }} 
                        (Rp {{ number_format($service->service_price ?? 0, 0, ',', '.') }})
                    </option>
                @endforeach
            @else
                <option value="">No services available</option>
            @endif
        </select>
        @if(!$services || $services->count() == 0)
            <small style="color: red;">No services found. Run /setup-admin to create services.</small>
        @endif
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Employee:</strong></label><br>
        <select name="employee_id" style="width: 100%; padding: 8px;" required>
            <option value="">Select Employee</option>
            @if($employees && $employees->count() > 0)
                @foreach($employees as $employee)
                    <option value="{{ $employee->employee_id }}">{{ $employee->employee_name }}</option>
                @endforeach
            @else
                <option value="">No employees available</option>
            @endif
        </select>
        @if(!$employees || $employees->count() == 0)
            <small style="color: red;">No employees found. Run /setup-admin to create employees.</small>
        @endif
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Item Name:</strong></label><br>
        <input type="text" name="item_name" style="width: 100%; padding: 8px;" required>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Item Type:</strong></label><br>
        <select name="item_type" style="width: 100%; padding: 8px;" required>
            <option value="">Select Type</option>
            <option value="Hardware">Hardware</option>
            <option value="Software">Software</option>
            <option value="Printer">Printer</option>
            <option value="PC">PC</option>
            <option value="Laptop">Laptop</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Status/Description:</strong></label><br>
        <textarea name="item_status" style="width: 100%; height: 100px; padding: 8px;" placeholder="Describe the problem or status..."></textarea>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Location:</strong></label><br>
        <select name="location_id" style="width: 100%; padding: 8px;" required>
            <option value="">Select Location</option>
            @if($locations && $locations->count() > 0)
                @foreach($locations as $location)
                    <option value="{{ $location->office_id }}">{{ $location->office_address }}</option>
                @endforeach
            @else
                <option value="">No locations available</option>
            @endif
        </select>
        @if(!$locations || $locations->count() == 0)
            <small style="color: red;">No locations found. Run /setup-admin to create locations.</small>
        @endif
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Total Price:</strong></label><br>
        <input type="number" name="total_price" id="total_price" style="width: 100%; padding: 8px;" step="0.01" required>
        <small>Will auto-fill based on service selection</small>
    </div>

    <div style="margin: 15px 0;">
        <label><strong>Status:</strong></label><br>
        <select name="status_id" style="width: 100%; padding: 8px;" required>
            @foreach($transactionStatuses as $status)
                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" style="background: green; color: white; padding: 15px 30px; border: none; cursor: pointer;">
        Create Order
    </button>
</form>

<script>
// Auto-fill price when service is selected
document.querySelector('select[name="service_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const priceInput = document.getElementById('total_price');
    
    if (price && priceInput) {
        priceInput.value = price;
        console.log('Price set to:', price); // Debug
    }
});

// Juga auto-fill saat halaman dimuat jika ada service terpilih
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.querySelector('select[name="service_id"]');
    if (serviceSelect.value) {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const priceInput = document.getElementById('total_price');
        
        if (price && priceInput) {
            priceInput.value = price;
        }
    }
});
</script>
@endsection
