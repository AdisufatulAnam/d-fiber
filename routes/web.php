<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/users', [UserController::class, 'index'])->name('users.index');

/*
|--------------------------------------------------------------------------
| Admin operasional — login & dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
});

Route::post('/login', [AuthController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('login.store');

Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');
    Route::get('/admin/payments/scan/{qrCode}', [PaymentController::class, 'scan'])->name('admin.payments.scan');
    Route::get('/admin/payments/customer/{qrCode}', [PaymentController::class, 'scan'])->name('admin.payments.customer');
    Route::post('/admin/payments/find-customer', [PaymentController::class, 'findCustomerByQr'])->name('admin.payments.find-customer');
    Route::get('/admin/payments/customer/{customer}/bills', [PaymentController::class, 'customerBills'])->name('admin.payments.customer-bills');
    Route::post('/admin/payments/bulk', [PaymentController::class, 'payBulk'])->name('admin.payments.bulk');
    Route::post('/admin/payments', [PaymentController::class, 'store'])->name('admin.payments.store');
    Route::post('/admin/payments/bill/{customer}', [PaymentController::class, 'addBill'])->name('admin.payments.bill');
    Route::patch('/admin/payments/{payment}', [PaymentController::class, 'update'])->name('admin.payments.update');
    Route::delete('/admin/payments/{payment}', [PaymentController::class, 'cancel'])->name('admin.payments.cancel');
    Route::post('/admin/payments/{payment}/restore', [PaymentController::class, 'restore'])->withTrashed()->name('admin.payments.restore');
    Route::post('/admin/payments/{payment}/resend-wa', [PaymentController::class, 'resendWhatsApp'])->name('admin.payments.resend-wa');

    // Data Master: Pelanggan
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers');
    Route::post('/admin/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
    Route::patch('/admin/customers/{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('/admin/customers/{customer}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Data Master: Paket Langganan
    Route::get('/admin/packages', [PackageController::class, 'index'])->name('admin.packages');
    Route::post('/admin/packages', [PackageController::class, 'store'])->name('admin.packages.store');
    Route::patch('/admin/packages/{package}', [PackageController::class, 'update'])->name('admin.packages.update');
    Route::delete('/admin/packages/{package}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
});
