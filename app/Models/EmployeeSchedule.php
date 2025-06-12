<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'availability_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the employee that has this schedule
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the restaurant that owns the schedule
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the availability that this schedule is based on
     */
    public function availability()
    {
        return $this->belongsTo(EmployeeAvailability::class, 'availability_id');
    }

    /**
     * Get the user who created this schedule (restaurateur)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
