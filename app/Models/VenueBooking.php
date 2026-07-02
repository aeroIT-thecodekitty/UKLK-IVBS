<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueBooking extends Model
{
    use HasFactory;

    protected $table = 'venue_bookings';

    // THIS IS THE FIX: Disable automatic timestamp management
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'start_time',
        'end_time',
        'reason',
        'venue_id',
        'status',
    ];

    // Relationship matching the Blade view ($booking->venue)
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}