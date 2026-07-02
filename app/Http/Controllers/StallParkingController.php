<?php

namespace App\Http\Controllers;

use App\Models\StallParking;
use Illuminate\Http\Request;

class StallParkingController extends Controller
{
    public function stall_parking()
    {
        $stalls = StallParking::all();
        return view('admin_user.stall_parking', compact('stalls'));
    }

    public function stall_parking_create()
    {
        return view('admin_user.stall_parking_create');
    }

    public function stall_parking_store(Request $request)
    {
        $validated = $request->validate([
            'stall_number' => 'required|string|max:250',
            'location'     => 'required|string|max:250',
            'is_roofed'    => 'required|boolean',
            'student_rate' => 'required|numeric|min:0',
            'vendor_rate'  => 'required|numeric|min:0',
            'total_slots'  => 'required|integer|min:1',
            'status'       => 'required|string|in:available,maintenance',
        ]);

        StallParking::create($validated);

        return redirect()->route('admin_user.stall_parking')->with('success', 'Parking slot registered successfully!');
    }

    public function stall_parking_edit($id)
    {
        $stall = StallParking::findOrFail($id);
        return view('admin_user.stall_parking_edit', compact('stall'));
    }

    public function stall_parking_update(Request $request, $id)
    {
        $stall = StallParking::findOrFail($id);

        $validated = $request->validate([
            'stall_number' => 'required|string|max:250',
            'location'     => 'required|string|max:250',
            'is_roofed'    => 'required|boolean',
            'student_rate' => 'required|numeric|min:0',
            'vendor_rate'  => 'required|numeric|min:0',
            'total_slots'  => 'required|integer|min:1',
            'status'       => 'required|string|in:available,maintenance',
        ]);

        $stall->update($validated);

        return redirect()->route('admin_user.stall_parking')->with('success', 'Parking slot updated successfully!');
    }

    public function stall_parking_destroy($id)
    {
        $stall = StallParking::findOrFail($id);
        $stall->delete();

        return redirect()->route('admin_user.stall_parking')->with('success', 'Parking slot deleted successfully!');
    }
}