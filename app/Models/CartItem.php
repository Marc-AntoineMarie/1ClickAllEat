<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'table_id',
        'guests_count',
        'reservation_time',
        'notes',
        'total_price',
        'selected_items',
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
        'selected_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
