<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeAvailability extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'date',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the employee that owns the availability
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the restaurant that owns the availability
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the schedules associated with this availability
     */
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'availability_id');
    }
}
