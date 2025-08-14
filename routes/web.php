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
    
    // Admin route untuk manage items
    Route::get('/items', function() {
        $items = \App\Models\Item::with(['transactions'])->get();
        return view('admin.items.index', compact('items'));
    })->name('items.index');
    
    Route::delete('/items/{id}', function($id) {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Hapus semua transactions yang menggunakan item ini dulu
            $deletedTransactions = \App\Models\Transaction::where('item_id', $id)->delete();
            
            // Kemudian hapus item
            $item = \App\Models\Item::findOrFail($id);
            $item->delete();
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('admin.items.index')
                ->with('success', "Item deleted successfully! Also removed {$deletedTransactions} related transactions.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->route('admin.items.index')
                ->with('error', 'Error deleting item: ' . $e->getMessage());
        }
    })->name('items.destroy');
    
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
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $transaction = \App\Models\Transaction::findOrFail($id);
            
            // Simpan item_id untuk menghapus item setelah transaction
            $item_id = $transaction->item_id;
            
            // Hapus transaction dulu
            $transaction->delete();
            
            // Kemudian hapus item jika tidak digunakan oleh transaction lain
            if ($item_id) {
                $otherTransactions = \App\Models\Transaction::where('item_id', $item_id)->count();
                if ($otherTransactions == 0) {
                    \App\Models\Item::where('item_id', $item_id)->delete();
                }
            }
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->route('admin.orders.index')->with('error', 'Error deleting order: ' . $e->getMessage());
        }
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

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'authenticate')->name('authenticate');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::group(['middleware' => ['auth:web']], function () {

        Route::group(['prefix' => 'cms', 'as' => 'cms.'], function () {
            Route::resource('newsrooms', NewsroomController::class)->except('show');
            Route::group(['prefix' => 'newsrooms', 'as' => 'newsrooms.'], function () {
                Route::post('data', [NewsroomController::class, 'data'])->name('data');
            });

            Route::resource('founders', FounderController::class)->except('show');
            Route::group(['prefix' => 'founders', 'as' => 'founders.'], function () {
                Route::post('data', [FounderController::class, 'data'])->name('data');
            });

            Route::group(['prefix' => 'contacts', 'as' => 'contacts.'], function () {
                Route::get('contacts', [ContactController::class, 'index'])->name('index');
                Route::post('data', [ContactController::class, 'data'])->name('data');
            });
        });

        Route::group(['prefix' => 'master_data', 'as' => 'master_data.'], function () {
            Route::group(['prefix' => 'talents', 'as' => 'talents.'], function () {
                Route::post('data', [TalentController::class, 'data'])->name('data');

                Route::group(['prefix' => '{talent}'], function () {
                    Route::resource('talent_categories', TalentCategoryController::class)->only('index', 'store', 'destroy');
                    Route::group(['prefix' => 'talent_categories', 'as' => 'talent_categories.'], function () {
                        Route::post('data', [TalentCategoryController::class, 'data'])->name('data');
                        Route::get('get-categories-by-talent', [TalentCategoryController::class, 'getCategoriesByTalent'])->name('get_categories_by_talent');
                    });

                    Route::resource('talent_photo', TalentPhotoController::class)->except('show');
                    Route::group(['prefix' => 'talent_photo', 'as' => 'talent_photo.'], function () {
                        Route::post('data', [TalentPhotoController::class, 'data'])->name('data');
                    });

                    Route::resource('talent_price', TalentPriceController::class)->except('show');
                    Route::group(['prefix' => 'talent_price', 'as' => 'talent_price.'], function () {
                        Route::post('data', [TalentPriceController::class, 'data'])->name('data');
                    });

                    Route::resource('talent_spotlight', TalentSpotlightController::class)->except('show');
                    Route::group(['prefix' => 'talent_spotlight', 'as' => 'talent_spotlight.'], function () {
                        Route::post('data', [TalentSpotlightController::class, 'data'])->name('data');
                    });

                    Route::resource('talent_rating', TalentRatingController::class)->except('show');
                    Route::group(['prefix' => 'talent_rating', 'as' => 'talent_rating.'], function () {
                        Route::post('data', [TalentRatingController::class, 'data'])->name('data');
                    });
                });
            });
            Route::resource('talents', TalentController::class);

            Route::resource('categories', CategoryController::class)->except('show');
            Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
                Route::post('data', [CategoryController::class, 'data'])->name('data');
            });

            Route::resource('art-categories', ArtCategoryController::class)->except('show');
            Route::group(['prefix' => 'art-categories', 'as' => 'art-categories.'], function () {
                Route::post('data', [ArtCategoryController::class, 'data'])->name('data');
            });

            Route::resource('professional-categories', ProfessionalCategoryController::class)->except('show');
            Route::group(['prefix' => 'professional-categories', 'as' => 'professional-categories.'], function () {
                Route::post('data', [ProfessionalCategoryController::class, 'data'])->name('data');
            });

            Route::resource('candidate-talents', CandidateTalentController::class)->only('index', 'edit', 'update');
            Route::group(['prefix' => 'candidate-talents', 'as' => 'candidate-talents.'], function () {
                Route::post('data', [CandidateTalentController::class, 'data'])->name('data');
            });

            Route::resource('members', MemberController::class)->except('show');
            Route::group(['prefix' => 'members', 'as' => 'members.'], function () {
                Route::post('data', [MemberController::class, 'data'])->name('data');
            });
        });

        Route::group(['prefix' => 'e-commerce', 'as' => 'e-commerce.'], function () {
            Route::resource('booking', BookingController::class)->except('show');
            Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {
                Route::get('{booking}/show', [BookingController::class, 'show'])->name('show');
                Route::post('data', [BookingController::class, 'data'])->name('data');
                Route::get('is-paid', [BookingController::class, 'indexIsPaid'])->name('is_paid');
                Route::post('is-paid/data', [BookingController::class, 'dataIsPaid'])->name('is_paid.data');
            });

            Route::resource('schedule', ScheduleController::class)->only('index', 'edit', 'update');
            Route::group(['prefix' => 'schedule', 'as' => 'schedule.'], function () {
                Route::post('data', [ScheduleController::class, 'data'])->name('data');
            });
        });
    });
});