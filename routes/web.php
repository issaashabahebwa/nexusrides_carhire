<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Customer Routes (auth required) ──────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Bookings
    Route::get('/bookings',               [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create',        [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',              [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}',     [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payments
    Route::get('/bookings/{booking}/pay',  [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payments.store');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Vehicles CRUD
    Route::get('/vehicles',                  [Admin\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create',           [Admin\VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles',                 [Admin\VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}/edit',   [Admin\VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}',        [Admin\VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}',     [Admin\VehicleController::class, 'destroy'])->name('vehicles.destroy');

    // Bookings management
    Route::get('/bookings',                        [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}',              [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/approve',     [Admin\BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/decline',     [Admin\BookingController::class, 'decline'])->name('bookings.decline');
    Route::post('/bookings/{booking}/complete',    [Admin\BookingController::class, 'complete'])->name('bookings.complete');

    // Customers
    Route::get('/customers',          [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}',   [Admin\CustomerController::class, 'show'])->name('customers.show');

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
});
