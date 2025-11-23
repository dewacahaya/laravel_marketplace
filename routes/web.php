<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;


use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use \App\Http\Controllers\Vendor\OrderController as VendorOrderController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ProductController as CustProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Models\Category;

// Halaman utama
Route::get('/', fn() => view('welcome'))->name('welcome');

// Redirect setelah login sesuai role
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'vendor' => redirect()->route('vendor.dashboard'),
        default => redirect()->route('customer.dashboard'),
    };
})->name('dashboard');


// Profil user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================
// DASHBOARD PER ROLE
// ======================
Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('categories/add-child', [CategoryController::class, 'addChild'])->name('categories.addChild');
        Route::post('categories/store-child', [CategoryController::class, 'storeChild'])->name('categories.storeChild');
        Route::get('categories/children/{parentId}', function ($parentId) {
            return Category::where('parent_id', $parentId)->get();
        });
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['create', 'store']);
        Route::resource('vendors', VendorController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
    });

    // VENDOR
    Route::middleware('role:vendor')->prefix('vendor')->as('vendor.')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', VendorProductController::class);
        Route::resource('orders', VendorOrderController::class);
        Route::patch('orders/{orderId}/status', [VendorOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    // CUSTOMER
    Route::middleware('role:customer')->prefix('customer')->as('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/product/{slug}', [CustProductController::class, 'show'])->name('product.detail');
        Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

        // CART (session-based)
        Route::resource('cart', CartController::class)->only(['index', 'update', 'destroy']);
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add'); // tambahan untuk add
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        // ORDER / CHECKOUT
        Route::resource('orders', CustomerOrderController::class);
        Route::get('/checkout/confirm', [CustomerOrderController::class, 'confirm'])->name('checkout.confirm');
        Route::post('/checkout/process', [CustomerOrderController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/success/{order}', [CustomerOrderController::class, 'success'])->name('checkout.success');
    });
});

require __DIR__ . '/auth.php';
