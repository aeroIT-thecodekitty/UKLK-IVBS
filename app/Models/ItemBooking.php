<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBooking extends Model
{
    use HasFactory;

    protected $table = 'item_bookings';

    protected $fillable = [
        'booking_id', 
        'item_id',
        'quantity_requested',
        'start_time',
        'end_time',
        'returned_at',
        'return_condition',
        'reason',
        'status',
    ];

    // Ensures Carbon can parse these automatically in your Blade files
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // Relationship matching the Blade view ($booking->item)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}