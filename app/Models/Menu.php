<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'date', 'is_daily', 'promotion'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_menu');
    }

    public function isActiveToday()
    {
        if ($this->is_daily && $this->date) {
            return $this->date == now()->toDateString();
        }
        return !$this->is_daily;
    }
}
