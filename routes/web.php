<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');

// Routes authentifiées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'restaurateur') {
            return redirect()->route('restaurateur.dashboard');
        }
        
        $restaurants = \App\Models\Restaurant::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('ratings_avg_rating')
            ->paginate(6);
        
        return view('dashboard', compact('restaurants'));
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Réservations
    Route::get('/restaurants/{restaurant}/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/restaurants/{restaurant}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Panier
    Route::middleware(['auth'])->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/{restaurant}', [CartController::class, 'store'])->name('cart.store');
        Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/cart/process-payment', [CartController::class, 'processPayment'])->name('cart.process-payment');
    });

    // Restaurateur
    Route::group([
        'prefix' => 'restaurateur',
        'as' => 'restaurateur.',
        'middleware' => 'auth'
    ], function () {
        Route::get('/dashboard', function () {
            if (Auth::user()->role !== 'restaurateur') {
                abort(403, 'Accès non autorisé');
            }
            return view('restaurateur.dashboard');
        })->name('dashboard');

        Route::get('/restaurants', [RestaurantController::class, 'indexForRestaurateur'])->name('restaurants.index');
        Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
        Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
        Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');

        Route::resource('menus', MenuController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    });

    // Administrateurs
    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => 'auth'
    ], function () {
        Route::get('/dashboard', function () {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Accès non autorisé');
            }
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/users', function () {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Accès non autorisé');
            }
            return view('admin.users.index');
        })->name('users.index');
    });
});

require __DIR__.'/auth.php';
