<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentBookingController extends Controller
{
    // 1. Handle Stall Bookings
    public function storeStall(Request $request)
    {
        $request->validate([
            'stall_id' => 'required|integer',
            'booking_date' => 'required|date',
            'company' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50',
        ]);

        // Fetch student rate directly from master stall_parking record
        $stallPrice = DB::table('stall_parking')->where('id', $request->stall_id)->value('student_rate') ?? 0;

        DB::table('stall_bookings')->insert([
            'stall_id' => $request->stall_id,
            'booking_date' => $request->booking_date,
            'company' => $request->company,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
            'amount' => $stallPrice,
            'userid' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect()->route('student.my_bookings')->with('success', 'Stall booking request submitted successfully!');
    }

    // 2. Handle Cabin Bookings
    public function storeCabin(Request $request)
    {
        $request->validate([
            'cabin_id' => 'required|integer',
            'booking_date' => 'required|date',
            'company' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50',
        ]);

        // Fetch current rate directly from master cibo_cabin record
        $cabinPrice = DB::table('cibo_cabin')->where('id', $request->cabin_id)->value('rate') ?? 0;

        DB::table('cabin_bookings')->insert([
            'cabin_id' => $request->cabin_id,
            'booking_date' => $request->booking_date,
            'company' => $request->company,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
            'amount' => $cabinPrice,
            'userid' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect()->route('student.my_bookings')->with('success', 'Cibo Cabin booking request submitted successfully!');
    }

    // 3. Handle Venue/Lounge Bookings
    public function storeVenue(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string',
        ]);

        DB::table('venue_bookings')->insert([
            'venue_id' => $request->venue_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending',
            'userid' => Auth::id(),
        ]);

        return redirect()->route('student.my_bookings')->with('success', 'Venue reservation request submitted successfully!');
    }

    // 4. Handle Equipment/Item Borrowing
    public function storeEquipment(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'quantity_requested' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string',
        ]);

        DB::table('item_bookings')->insert([
            'item_id' => $request->item_id,
            'quantity_requested' => $request->quantity_requested,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending',
            'userid' => Auth::id(),
        ]);

        return redirect()->route('student.my_borrows')->with('success', 'Equipment borrowing request submitted successfully!');
    }
}