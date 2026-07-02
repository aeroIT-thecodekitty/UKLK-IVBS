<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StallParking extends Model
{
    protected $table = 'stall_parking';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'stall_number', 
        'location', 
        'is_roofed', 
        'student_rate', 
        'vendor_rate', 
        'total_slots', 
        'status'
    ];

    public $timestamps = false;

    /**
     * Relationship: A parking space can have multiple history bookings over time
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(StallBooking::class, 'stall_id', 'id');
    }
}