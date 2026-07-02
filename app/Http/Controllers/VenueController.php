<?php

namespace App\Http\Controllers;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller {
    public function lounge() {
        $venues = Venue::all();
        return view('admin_user.lounge', compact('venues'));
    }
    public function store(Request $request) {
        Venue::create($request->all());
        return back()->with('success', 'Lounge added.');
    }
    public function update(Request $request, $id) {
        Venue::findOrFail($id)->update($request->all());
        return back()->with('success', 'Lounge updated.');
    }
    public function destroy($id) {
        Venue::destroy($id);
        return back()->with('success', 'Lounge deleted.');
    }
}