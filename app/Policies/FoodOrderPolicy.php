<?php

namespace App\Policies;

use App\Models\FoodOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FoodOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FoodOrder $order): bool
    {
        return $user->id === $order->client_id || 
               $user->id === $order->restaurant->owner_id ||
               $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->name === 'client';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FoodOrder $order): bool
    {
        return $user->id === $order->restaurant->owner_id ||
               $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, FoodOrder $order): bool
    {
        if ($order->status === 'cancelled' || $order->status === 'delivered') {
            return false;
        }

        return $user->id === $order->client_id ||
               $user->id === $order->restaurant->owner_id ||
               $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FoodOrder $order): bool
    {
        return $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FoodOrder $order): bool
    {
        return $user->role->name === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FoodOrder $order): bool
    {
        return $user->role->name === 'admin';
    }
}
