<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinBooking extends Model
{
    use HasFactory;

    // Explicitly bind to the Supabase table name
    protected $table = 'cabin_bookings';

    protected $fillable = [
        'cabin_id',
        'booking_date',
        'company',
        'full_name',
        'phone_number',
        'status',
        'amount',
        'gateway_reference',
        'receipt_url',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationship matching the Blade view ($booking->ciboCabin)
    public function ciboCabin()
    {
        // Change 'CiboCabin::class' to whatever your actual Cabin model is named
        return $this->belongsTo(CiboCabin::class, 'cabin_id'); 
    }
}