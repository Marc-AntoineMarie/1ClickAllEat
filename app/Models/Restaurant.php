<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use App\Models\FoodOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adresse',
        'description',
        'globalNote',
        'openHours',
        'capacity',
        'owner_id',
        'opening_hours'
    ];

    protected $casts = [
        'opening_hours' => 'json'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('score') ?? 0;
    }

    public function ratingCount()
    {
        return $this->ratings()->count();
    }

    public function orders()
    {
        return $this->hasMany(FoodOrder::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function menus()
    {
        return $this->hasMany(\App\Models\Menu::class);
    }
}
