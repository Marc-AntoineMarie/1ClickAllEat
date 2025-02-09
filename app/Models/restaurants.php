<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model 
{
    use HasFactory;

    protected $table = "restaurants";

    protected $fillable = [
        "nom",
        "description",
        "place_max",
        "place_occuper",
        "place_disponible",
        'image',
    ];

    public function scopeGetRestaurantById($query, $id)
    {
        return $query->where('id', $id);
    }
}