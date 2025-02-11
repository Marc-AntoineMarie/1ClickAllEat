<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{

    use HasFactory;

    protected $table = "restaurants";

    protected $fillable = [
        "name"
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}