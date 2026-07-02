<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // 1. Tell Laravel the primary key isn't named 'id'
    protected $primaryKey = 'booking_id';

    // 2. Tell Laravel which fields are safe to fill via forms
    protected $fillable = ['id', 'event_name', 'purpose', 'status', 'admin_notes'];
}