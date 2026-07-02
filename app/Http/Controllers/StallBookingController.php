<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StallBooking;

class StallBookingController extends Controller
{
    /**
     * Display the segmented inventory dashboard overview.
     */
    public function stall_bookings()
    {
        // PENDING QUEUE: Captures raw 'pending' alongside workflow variations
        $pendingBookings = StallBooking::whereIn('status', [
                'pending',
                'pending_approval', 
                'awaiting_payment', 
                'pending_payment_verification'
            ])
            ->with('stallParking')
            ->orderBy('created_at', 'desc')
            ->get();

        // CONFIRMED ACTIVE QUEUE: Final validated active lease slots
        $confirmedBookings = StallBooking::whereIn('status', [
                'payment_confirmed', 
                'confirmed', 
                'approved'
            ])
            ->with('stallParking')
            ->orderBy('created_at', 'desc')
            ->get();

        // PAST / HISTORICAL ARCHIVE LOGS: Locked out final data snapshots
        $pastBookings = StallBooking::whereIn('status', [
                'past', 
                'rejected', 
                'cancelled'
            ])
            ->with('stallParking')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_user.stall_bookings', compact('pendingBookings', 'confirmedBookings', 'pastBookings'));
    }
    
    /**
     * Update the booking workflow stage lifecycle state dynamically.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $booking = StallBooking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        $message = 'Booking updated successfully.';
        if ($request->status === 'awaiting_payment') {
            $message = 'Slot space verified! Student has been unlocked to process payment actions.';
        } elseif ($request->status === 'payment_confirmed') {
            $message = 'Financial transaction receipt verified! Booking is now active and live.';
        } elseif ($request->status === 'rejected') {
            $message = 'Booking request has been declined and cancelled.';
        }

        return redirect()->back()->with('success', $message);
    }
}