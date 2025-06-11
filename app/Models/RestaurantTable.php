<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Restaurant;
use App\Models\Order;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'capacity',
        'restaurant_id'
    ];

    protected $appends = ['is_available'];

    public function getIsAvailableAttribute()
    {
        return !$this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('date_reservation', '>=', now())
            ->exists();
    }

    public function getCurrentOrderAttribute()
    {
        return $this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('dateReservation', '>=', now())
            ->orderBy('dateReservation')
            ->first();
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    public function currentReservation()
    {
        return $this->hasOne(Reservation::class, 'table_id')
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->latest();
    }
}
