<?php

namespace App\Providers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Autorisation de supprimer une commande (FoodOrder)
        \Illuminate\Support\Facades\Gate::define('delete-order', function ($user, $order) {
            return $user->id === $order->client_id
                || $user->id === $order->restaurant->owner_id
                || $user->role->name === 'admin';
        });
        // Autorisation de créer un restaurant
        Gate::define('create-restaurant', function (User $user) {
            return $user->role->name === 'restaurateur';
        });

        // Autorisation de mettre à jour un restaurant
        Gate::define('update', function (User $user, Restaurant $restaurant) {
            return $user->id === $restaurant->owner_id || $user->role->name === 'admin';
        });

        // Autorisation de supprimer un restaurant
        Gate::define('delete', function (User $user, Restaurant $restaurant) {
            return $user->id === $restaurant->owner_id || $user->role->name === 'admin';
        });
    }
}
