<?php

namespace App\Http\Controllers;

use App\Models\ItemBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemBookingController extends Controller 
{
    /**
     * Display the item borrowing queue lists.
     */
    public function booking() {
        $bookings = ItemBooking::with('item')->get();
        
        $pendingBookings = $bookings->where('status', 'pending');
        $confirmedBookings = $bookings->where('status', 'approved');
        
        // UPDATED: Added 'completed' status so archived items stay visible in this tab
        $pastBookings = $bookings->whereIn('status', ['returned', 'rejected', 'completed']);
        
        return view('admin_user.item_bookings', compact('pendingBookings', 'confirmedBookings', 'pastBookings'));
    }

    /**
     * Update status without triggering automatic Eloquent updated_at timestamps.
     */
    public function updateStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|string',
            'return_condition' => 'nullable|string|max:255' // Added validation for evaluation input
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // UPDATED: Injects the return evaluation log if passed from the admin view form
        if ($request->has('return_condition')) {
            $updateData['return_condition'] = $request->return_condition;
        }

        // Uses DB lookup directly to avoid the missing updated_at constraint error on Supabase
        DB::table('item_bookings')
            ->where('id', $id)
            ->update($updateData);

        return back()->with('success', 'Item status updated successfully.');
    }
}