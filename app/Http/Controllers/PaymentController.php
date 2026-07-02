<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StallBooking;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function uploadReceipt(Request $request)
    {
        // Strict file validation rules to prevent corrupt file injection
        $request->validate([
            'booking_id' => 'required',
            'receipt'    => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $bookingId = $request->input('booking_id');

        try {
            $booking = StallBooking::find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'Booking record not found.'], 404);
            }

            // Stream the receipt file into your server's local public storage disk
            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                
                // Map the asset link string to your Supabase table field
                $booking->receipt_url = Storage::url($path);
                
                // Roll status back to verification so the admin panel highlights it
                $booking->status = 'pending_verification'; 
                $booking->save();
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}