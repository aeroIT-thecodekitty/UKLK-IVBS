<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'status',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // Relationship: A Venue can have many bookings
    public function venueBookings()
    {
        return $this->hasMany(VenueBooking::class, 'venue_id');
    }
}