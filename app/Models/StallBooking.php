<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StallBooking extends Model
{
    protected $table = 'stall_bookings';
    
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'stall_id', 
        'full_name', 
        'company', 
        'booking_date', 
        'status'
    ];

    /**
     * Relationship: Each booking belongs to a specific stall layout
     */
    public function stallParking(): BelongsTo
    {
        return $this->belongsTo(StallParking::class, 'stall_id', 'id');
    }
}