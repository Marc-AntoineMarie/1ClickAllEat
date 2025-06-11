<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\OrderItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'item_menu');
    }

    public function isSoldOut()
    {
        return !$this->disponibility;
    }

    public function getEffectivePriceAttribute()
    {
        if ($this->promotion) {
            return round($this->prix * (1 - $this->promotion / 100), 2);
        }
        return $this->prix;
    }

    protected $fillable = [
        'name',
        'prix',
        'description',
        'disponibility',
        'restaurant_id',
        'category_id'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
