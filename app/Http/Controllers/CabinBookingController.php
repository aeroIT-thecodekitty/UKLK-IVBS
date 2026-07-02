<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabinBookingController extends Controller 
{
    /**
     * Display the segmented cabin bookings overview to the admin panel.
     */
    public function booking() 
    {
        // Performs a left join against cibo_cabin to securely pull down real-time asset names
        $bookings = DB::table('cabin_bookings')
            ->leftJoin('cibo_cabin', 'cabin_bookings.cabin_id', '=', 'cibo_cabin.id')
            ->select('cabin_bookings.*', 'cibo_cabin.cabin_number', 'cibo_cabin.location')
            ->orderBy('cabin_bookings.created_at', 'desc')
            ->get();
        
        // PENDING QUEUE: Captures initial requests, verified slots, and submitted receipts
        $pendingBookings = $bookings->whereIn('status', [
            'pending',
            'pending_approval',
            'awaiting_payment',
            'pending_payment_verification'
        ]);

        // CONFIRMED ACTIVE QUEUE: Approved leases after cash audit completion
        $confirmedBookings = $bookings->whereIn('status', [
            'payment_confirmed',
            'confirmed',
            'approved'
        ]);

        // HISTORICAL ARCHIVE QUEUE: Past outdates, rejections, and system drops
        $pastBookings = $bookings->whereIn('status', [
            'past',
            'rejected',
            'cancelled',
            'completed'
        ]);
        
        return view('admin_user.cabin_bookings', compact('pendingBookings', 'confirmedBookings', 'pastBookings'));
    }

    /**
     * Update cabin status without triggering automatic Eloquent updated_at column crashes.
     */
    public function updateStatus(Request $request, $id) 
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        // Bypasses Eloquent entirely to remain completely immune to missing timestamp column faults
        DB::table('cabin_bookings')
            ->where('id', $id)
            ->update([
                'status' => $request->status
            ]);

        // Contextual dynamic success alert messages
        $message = 'Cabin status tracking node shifted.';
        if ($request->status === 'awaiting_payment') {
            $message = 'Slot physical space validated! User has been unlocked to pay.';
        } elseif ($request->status === 'payment_confirmed') {
            $message = 'Payment receipt verified! Cabin slot is now officially locked and active.';
        } elseif ($request->status === 'rejected') {
            $message = 'Cabin booking lease application rejected.';
        }

        return back()->with('success', $message);
    }
}