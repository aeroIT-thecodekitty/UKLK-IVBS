<?php

namespace App\Http\Controllers;
use App\Models\CiboCabin;
use Illuminate\Http\Request;

class CabinController extends Controller {
    public function cabin() {
        $cabins = CiboCabin::all();
        return view('admin_user.cabin', compact('cabins'));
    }
    public function store(Request $request) {
        CiboCabin::create($request->all());
        return back()->with('success', 'Cabin added successfully.');
    }
    public function update(Request $request, $id) {
        CiboCabin::findOrFail($id)->update($request->all());
        return back()->with('success', 'Cabin updated.');
    }
    public function destroy($id) {
        CiboCabin::destroy($id);
        return back()->with('success', 'Cabin deleted.');
    }
}