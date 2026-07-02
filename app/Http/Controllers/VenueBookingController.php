<?php

namespace App\Http\Controllers;
use App\Models\VenueBooking;
use Illuminate\Http\Request;

class VenueBookingController extends Controller {
    public function booking() {
        $bookings = VenueBooking::with('venue')->get();
        $pendingBookings = $bookings->where('status', 'pending');
        $confirmedBookings = $bookings->where('status', 'approved');
        $pastBookings = $bookings->whereIn('status', ['past', 'rejected']);
        return view('admin_user.lounge_bookings', compact('pendingBookings', 'confirmedBookings', 'pastBookings'));
    }
    public function updateStatus(Request $request, $id) {
        VenueBooking::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Lounge booking updated.');
    }
}