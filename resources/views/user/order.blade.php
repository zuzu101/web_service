@extends('layout')

@section('content')
<h1>Create New Order</h1>

<div style="margin: 20px 0;">
    <a href="{{ route('user.dashboard') }}" style="background: gray; color: white; padding: 10px; text-decoration: none;">Back to Dashboard</a>
</div>

<form method="POST" action="{{ route('user.order.store') }}">
    @csrf
    
    <div style="margin-bottom: 15px;">
        <label for="order_description">Describe your problem (like WhatsApp message):</label><br>
        <textarea 
            name="order_description" 
            id="order_description" 
            rows="10" 
            cols="80" 
            style="width: 100%; padding: 10px; border: 1px solid #ccc;"
            placeholder="Example: 
Hi admin, saya ada masalah dengan laptop saya. Laptopnya merk ASUS ROG, masalahnya:
- Laptop sering hang ketika buka aplikasi berat
- Kipas bunyi keras banget
- Panas banget di bagian bawah
- Kadang blue screen

Alamat saya: Jl. Kebon Jeruk No. 123, Jakarta Barat
HP: 081234567890

Mohon bantuannya ya admin. Terima kasih!"
        >{{ old('order_description') }}</textarea>
    </div>
    
    <div style="margin-bottom: 15px;">
        <button type="submit" style="background: blue; color: white; padding: 15px 30px; border: none; cursor: pointer;">
            Send Order to Admin
        </button>
    </div>
</form>

<div style="background: #f0f0f0; padding: 15px; margin-top: 20px;">
    <h3>Available Services:</h3>
    <ul>
        <li>Service Hardware - Rp 150,000</li>
        <li>Service Software - Rp 100,000</li>
        <li>Maintenance & Cleaning - Rp 75,000</li>
        <li>Service Printer & PC - Rp 120,000</li>
    </ul>
    <p><em>Admin will create the official order based on your description above.</em></p>
</div>
@endsection
