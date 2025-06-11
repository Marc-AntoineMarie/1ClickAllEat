<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'order_id',
        'item_id'
    ];

    public function order()
    {
        return $this->belongsTo(FoodOrder::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
