<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\PaymentController;
use App\Models\admin;
// use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register/selectrole', [RegisterController::class, 'showRoleSelection'])
    ->name('register.form');

// 2. Point {role} to showRegister so it actually passes $role to register.blade.php
Route::get('/register/{role}', [RegisterController::class, 'showRegister'])
    ->name('register');

    Route::post('/auth/register', [RegisterController::class, 'register'])
    ->name('register.store');

Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/auth/reset', [AuthController::class, 'showReset'])->name('reset');
Route::post('/auth/reset', [AuthController::class, 'reset'])->name('auth.reset');

Route::get('/auth/verify', [AuthController::class, 'showVerify'])->name('auth.verify');
Route::post('/auth/verify', [AuthController::class, 'verifyCode'])->name('auth.verify.submit');

Route::get('/auth/password/create', [AuthController::class, 'showCreate'])->name('auth.password.create');
Route::post('/auth/password/update', [AuthController::class, 'updatePassword'])->name('auth.password.update');


// Dashboard
Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Product routes
Route::get('/products/addproduct', [ProductController::class, 'showAddProduct'])->name('addProduct');
Route::post('/products/addproduct', [ProductController::class, 'addProduct'])->name('products.addProduct');

Route::get('/products/product', [ProductController::class, 'showProduct'])->name('products.product');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Edit Page & Update Action
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');


// Buyer Route
Route::get('/buyer/dashboard', [BuyerController::class, 'buyerdash'])->name('buyer.dashboard');
Route::get('/buyer/browse', [BuyerController::class, 'buyerCategoryDash'])->name('buyer.browse');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('buyer.product.show');

// seller Route
Route::get('/seller/dashboard', [ProductController::class, 'sellerdash'])->name('seller.dashboard');
// Route::get('/seller/dashboard', function () {
//     return 'Seller dashboard route works!';
// })->name('seller.dashboard');

// cart route
Route::get('/buyer/cart', [BuyerController::class, 'showCart'])->name('buyer.cart');
Route::post('/buyer/cart', [BuyerController::class, 'addToCart'])->name('buyer.addToCart');
Route::post('/buyer/cart/update', [BuyerController::class, 'updateCart'])->name('buyer.updateCart');
Route::post('/buyer/cart/remove', [BuyerController::class, 'removeFromCart'])->name('buyer.removeFromCart');

// order /  route
Route::get('/buyer/order', [BuyerController::class, 'showOrder'])->name('buyer.order');
Route::get('/buyer/checkout', [BuyerController::class, 'showCheckout'])->name('buyer.checkout');

//checkout/ paystack route
Route::post('/payment/initialize', [PaymentController::class, 'initialize_payment'])->name('payment.initialize');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback'); 

// admin route
Route::get('/admin', [AdminController::class, 'showAdmin'])->name('admin.index');
Route::get('/admin/product', [AdminController::class, 'showAdminProduct'])->name('admin.admin_product');

// admin approval or rejection
Route::patch('/admin/products/{product}/approve', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
Route::patch('/admin/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('admin.products.reject');
