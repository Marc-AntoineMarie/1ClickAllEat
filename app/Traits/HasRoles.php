<?php

namespace App\Traits;

trait HasRoles
{
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isRestaurateur(): bool
    {
        return $this->hasRole('restaurateur');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }
}
