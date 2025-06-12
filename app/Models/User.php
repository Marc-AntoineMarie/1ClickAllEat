<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id');
    }

    public function clientFoodOrders()
    {
        return $this->hasMany(FoodOrder::class, 'client_id');
    }

    public function restaurantFoodOrders()
    {
        return $this->hasManyThrough(
            FoodOrder::class,
            Restaurant::class,
            'owner_id',
            'restaurant_id'
        );
    }
    
    /**
     * Get all ratings submitted by this user
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    
    /**
     * Get all orders associated with this user (as client)
     * This relation is used for withCount in the admin dashboard
     */
    public function orders()
    {
        return $this->clientFoodOrders();
    }
    
    /**
     * Get all availabilities submitted by this user (as employee)
     */
    public function availabilities()
    {
        return $this->hasMany(EmployeeAvailability::class);
    }
    
    /**
     * Get all schedules assigned to this user (as employee)
     */
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }
    
    /**
     * Get all schedules created by this user (as restaurateur)
     */
    public function createdSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'created_by');
    }
    
    /**
     * Check if the user is an employee
     */
    public function isEmployee()
    {
        return $this->role && $this->role->name === 'employee';
    }
    
    /**
     * Check if the user is a restaurateur
     */
    public function isRestaurateur()
    {
        return $this->role && $this->role->name === 'restaurateur';
    }
}
