<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes publiques pour les restaurants
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create')->middleware('auth');
Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store')->middleware('auth');
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');

// Panier et commandes en ligne
Route::get('/orders', [App\Http\Controllers\FoodOrderController::class, 'index'])->name('orders.index')->middleware('auth');
Route::post('/orders', [App\Http\Controllers\FoodOrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders/{order}', [App\Http\Controllers\FoodOrderController::class, 'show'])->name('orders.show')->middleware('auth');
Route::delete('/orders/{order}', [App\Http\Controllers\FoodOrderController::class, 'destroy'])->name('orders.destroy')->middleware('auth');
Route::put('/orders/{order}/cancel', [App\Http\Controllers\FoodOrderController::class, 'cancel'])->name('orders.cancel')->middleware('auth');

// Réservation de table pour un restaurant
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantTableController;

Route::post('/restaurants/{restaurant}/tables/{table}/reserve', [ReservationController::class, 'store'])->name('restaurants.tables.reserve');
// Annulation d'une réservation (client)
Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel')->middleware('auth');

// Panier et commandes en ligne
use App\Http\Controllers\FoodOrderController;
Route::get('/cart', [FoodOrderController::class, 'index'])->name('cart.index');

// Gestion des tables pour les restaurateurs
Route::middleware(['auth'])->group(function () {
    Route::resource('restaurants.tables', \App\Http\Controllers\RestaurantTableController::class);
});

// Gestion des plats (items) pour les restaurateurs
use App\Http\Controllers\ItemController;
Route::middleware(['auth'])->group(function () {
    Route::resource('restaurants.items', ItemController::class);
});

// Dashboard restaurateur
use App\Http\Controllers\RestaurateurDashboardController;
Route::middleware(['auth'])->group(function () {
    Route::get('/restaurateur/dashboard', [RestaurateurDashboardController::class, 'index'])->name('restaurateur.dashboard');
    Route::get('/restaurateur/dashboard/{restaurant}', [RestaurateurDashboardController::class, 'show'])->name('restaurateur.dashboard.show');
});

// Routes d'administration
use App\Http\Controllers\AdminDashboardController;

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/restaurants', [AdminDashboardController::class, 'restaurants'])->name('restaurants');
    Route::get('/ratings', [AdminDashboardController::class, 'ratings'])->name('ratings');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    Route::delete('/ratings/{rating}', [AdminDashboardController::class, 'destroyRating'])->name('ratings.destroy');
});

require __DIR__.'/auth.php';
