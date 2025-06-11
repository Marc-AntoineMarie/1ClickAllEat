<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodOrder extends Model
{
    use HasFactory;
    
    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'food_orders';

    protected $fillable = [
        'client_id',
        'restaurant_id',
        'status',
        'total_price'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class);
    }
    
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'food_order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_items', 'order_id', 'item_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
    
    /**
     * Accesseur pour récupérer total_price via $order->total
     */
    public function getTotalAttribute()
    {
        return $this->total_price;
    }

    /**
     * Retourne le texte lisible pour le statut de la commande
     */
    public function getStatusTextAttribute()
    {
        $map = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'accepted' => 'Acceptée',
            'preparing' => 'En préparation',
            'ready' => 'Prête',
            'delivering' => 'En livraison',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];
        return $map[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Retourne la couleur Bootstrap pour le badge du statut
     */
    public function getStatusColorAttribute()
    {
        $map = [
            'pending' => 'secondary',
            'confirmed' => 'info',
            'accepted' => 'primary',
            'preparing' => 'warning',
            'ready' => 'success',
            'delivering' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];
        return $map[$this->status] ?? 'secondary';
    }
}
