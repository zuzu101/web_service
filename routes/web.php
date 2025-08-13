<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebAuthController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Setup admin route - hanya untuk testing
Route::get('/setup-admin', function() {
    $admin = \App\Models\User::where('user_id', 999)->first();
    
    if (!$admin) {
        \App\Models\User::create([
            'user_id' => 999,
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'full_name' => 'Administrator',
            'email' => 'admin@admin.com',
        ]);
    }
    
    // Buat data service juga
    $services = \App\Models\Service::count();
    if ($services == 0) {
        \App\Models\Service::create([
            'service_type' => 'Service Hardware',
            'service_price' => 150000,
        ]);
        
        \App\Models\Service::create([
            'service_type' => 'Service Software', 
            'service_price' => 100000,
        ]);
        
        \App\Models\Service::create([
            'service_type' => 'Maintenance & Cleaning',
            'service_price' => 75000,
        ]);
        
        \App\Models\Service::create([
            'service_type' => 'Service Printer & PC',
            'service_price' => 125000,
        ]);
    }
    
    // Buat data employee
    $employees = \App\Models\Employee::count();
    if ($employees == 0) {
        \App\Models\Employee::create([
            'employee_name' => 'Teknisi A',
            'employee_phone' => '081234567890',
            'employee_email' => 'teknisi1@company.com'
        ]);
        
        \App\Models\Employee::create([
            'employee_name' => 'Teknisi B',
            'employee_phone' => '081234567891',
            'employee_email' => 'teknisi2@company.com'
        ]);
    }
    
    // Buat data location - hapus yang lama dulu
    \App\Models\Location::truncate(); // Hapus semua data location lama
    
    \App\Models\Location::create([
        'office_address' => 'Ujung Berung, Bandung'
    ]);
    
    \App\Models\Location::create([
        'office_address' => 'Getaway, Bandung'
    ]);
    
    // Buat data status
    $statuses = \App\Models\TransactionStatus::count();
    if ($statuses == 0) {
        \App\Models\TransactionStatus::create([
            'status_name' => 'pending'
        ]);
        
        \App\Models\TransactionStatus::create([
            'status_name' => 'completed'
        ]);
        
        \App\Models\TransactionStatus::create([
            'status_name' => 'paid'
        ]);
    }
    
    return 'Setup complete! All data created.<br><br>' .
           'Admin - Username: admin, Password: admin123<br><br>' .
           'Services: Hardware (150k), Software (100k), Maintenance (75k), Printer&PC (125k)<br>' .
           'Employees: Teknisi A, Teknisi B<br>' .
           'Locations: Ujung Berung, Getaway<br>' .
           'Status: pending, completed, paid<br><br>' .
           '<a href="/admin/orders/create">Go to Create Order</a>';
});

// Auth routes - simple login/register/logout
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Main dashboard route (redirect berdasarkan role)
Route::get('/dashboard', [WebAuthController::class, 'dashboard'])->name('dashboard');

// Admin routes - prioritas pertama
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard dengan statistik
    Route::get('/dashboard', function() {
        $totalOrders = \App\Models\Transaction::count();
        $totalUsers = \App\Models\User::where('user_id', '!=', 999)->count();
        $pendingOrders = \App\Models\Transaction::where('status_id', 1)->count();
        $completedOrders = \App\Models\Transaction::where('status_id', 2)->count();
        
        $recentOrders = \App\Models\Transaction::with([
            'user', 'employee', 'item', 'transactionStatus'
        ])->orderBy('transaction_date', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalUsers', 'pendingOrders', 'completedOrders', 'recentOrders'
        ));
    })->name('dashboard');
    
    // Admin CRUD orders - langsung ke database tanpa resource controller
    Route::get('/orders', function() {
        $orders = \App\Models\Transaction::with([
            'user', 'employee', 'location', 'item', 'transactionStatus'
        ])->orderBy('transaction_date', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    })->name('orders.index');
    
    Route::get('/orders/create', function() {
        // Ambil semua data yang diperlukan dengan proper query
        $users = \App\Models\User::where('user_id', '!=', 999)
                    ->whereNotNull('username')
                    ->get();
        
        $employees = \App\Models\Employee::all();
        
        $services = \App\Models\Service::whereNotNull('service_type')
                       ->whereNotNull('service_price')
                       ->get();
        
        $locations = \App\Models\Location::all();
        
        $transactionStatuses = \App\Models\TransactionStatus::all();

        return view('admin.orders.create', compact('users', 'employees', 'services', 'locations', 'transactionStatuses'));
    })->name('orders.create');
    
    Route::post('/orders', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'employee_id' => 'required|exists:employees,employee_id',
            'service_id' => 'required|exists:services,service_id', // Tambahkan service_id
            'item_name' => 'required|string|max:100',
            'item_type' => 'required|string|max:50',
            'location_id' => 'required|exists:locations,office_id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:transaction_status,status_id',
        ]);

        $item = \App\Models\Item::create([
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'status' => $request->item_status ?? 'New item'
        ]);

        \App\Models\Transaction::create([
            'user_id' => $request->user_id,
            'employee_id' => $request->employee_id,
            'office_id' => $request->location_id,
            'item_id' => $item->item_id,
            'total_price' => $request->total_price,
            'status_id' => $request->status_id,
            'transaction_date' => now()->format('Y-m-d')
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully!');
    })->name('orders.store');
    
    Route::get('/orders/{id}', function($id) {
        $order = \App\Models\Transaction::with([
            'user', 'employee', 'item', 'location', 'transactionStatus'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    })->name('orders.show');
    
    Route::get('/orders/{id}/edit', function($id) {
        $order = \App\Models\Transaction::with([
            'user', 'employee', 'item', 'location', 'transactionStatus'
        ])->findOrFail($id);

        $users = \App\Models\User::where('user_id', '!=', 999)
                    ->whereNotNull('username')
                    ->get();
        $employees = \App\Models\Employee::all();
        $services = \App\Models\Service::whereNotNull('service_type')
                       ->whereNotNull('service_price')
                       ->get();
        $locations = \App\Models\Location::all();
        $transactionStatuses = \App\Models\TransactionStatus::all();

        return view('admin.orders.edit', compact('order', 'users', 'employees', 'services', 'locations', 'transactionStatuses'));
    })->name('orders.edit');
    
    Route::put('/orders/{id}', function(\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'employee_id' => 'required|exists:employees,employee_id',
            'service_id' => 'required|exists:services,service_id',
            'item_name' => 'required|string|max:100',
            'item_type' => 'required|string|max:50',
            'location_id' => 'required|exists:locations,office_id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:transaction_status,status_id',
        ]);

        $transaction = \App\Models\Transaction::findOrFail($id);
        
        // Update item
        if ($transaction->item) {
            $transaction->item->update([
                'item_name' => $request->item_name,
                'item_type' => $request->item_type,
                'status' => $request->item_status ?? $transaction->item->status
            ]);
        }

        // Update transaction
        $transaction->update([
            'user_id' => $request->user_id,
            'employee_id' => $request->employee_id,
            'office_id' => $request->location_id,
            'total_price' => $request->total_price,
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully!');
    })->name('orders.update');
    
    Route::delete('/orders/{id}', function($id) {
        $transaction = \App\Models\Transaction::findOrFail($id);
        
        // Delete item juga kalau mau
        if ($transaction->item) {
            $transaction->item->delete();
        }
        
        $transaction->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    })->name('orders.destroy');
});

// User routes - untuk customer biasa
Route::prefix('user')->name('user.')->group(function () {
    // User dashboard - lihat orders sendiri
    Route::get('/dashboard', function() {
        if (!Auth::check()) return redirect()->route('login');
        
        $userOrders = \App\Models\Transaction::with([
            'employee', 'item', 'transactionStatus'
        ])->where('user_id', Auth::id())
        ->orderBy('transaction_date', 'desc')->get();
        
        return view('user.dashboard', compact('userOrders'));
    })->name('dashboard');
    
    // User buat order baru
    Route::get('/order', function() {
        if (!Auth::check()) return redirect()->route('login');
        
        $services = \App\Models\Service::all();
        return view('user.order', compact('services'));
    })->name('order.create');
    
    Route::post('/order', function(\Illuminate\Http\Request $request) {
        if (!Auth::check()) return redirect()->route('login');
        
        $request->validate([
            'order_description' => 'required|string|max:1000',
        ]);

        // Simpan ke session untuk admin proses nanti
        session([
            'pending_order' => [
                'user_id' => Auth::id(),
                'description' => $request->order_description,
                'created_at' => now()
            ]
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Order berhasil dikirim! Admin akan memproses segera.');
    })->name('order.store');
    
    // User profile
    Route::get('/profile', function() {
        if (!Auth::check()) return redirect()->route('login');
        return view('user.profile');
    })->name('profile');
    
    Route::post('/profile', [WebAuthController::class, 'updateProfile'])->name('profile.update');
});
