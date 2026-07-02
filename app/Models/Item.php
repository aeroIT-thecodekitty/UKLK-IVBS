<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    // FIXED: Tells Laravel that the 'updated_at' column does not exist on this table
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'total_quantity',
        'status',
        'description',
    ];

    protected $casts = [
        'total_quantity' => 'integer',
    ];

    /**
     * Relationship: An Item can be requested in many bookings
     */
    public function itemBookings()
    {
        return $this->hasMany(ItemBooking::class, 'item_id');
    }
}