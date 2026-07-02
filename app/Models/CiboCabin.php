<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiboCabin extends Model
{
    use HasFactory;

    // Explicitly bind to the Supabase table name
    protected $table = 'cibo_cabin';

    protected $fillable = [
        'cabin_number',
        'location',
        'is_roofed',
        'rate',
        'total_slots',
        'status',
        'description',
    ];

    // Cast specific column types so Laravel handles them correctly
    protected $casts = [
        'is_roofed' => 'boolean',
        'rate' => 'decimal:2',
        'total_slots' => 'integer',
    ];

    // Relationship: A Cibo Cabin can have many bookings
    public function cabinBookings()
    {
        return $this->hasMany(CabinBooking::class, 'cabin_id');
    }
}