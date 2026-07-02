<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller {
    public function item() {
        $items = Item::all();
        return view('admin_user.item', compact('items'));
    }
    public function store(Request $request) {
        Item::create($request->all());
        return back()->with('success', 'Item added.');
    }
    public function update(Request $request, $id) {
        Item::findOrFail($id)->update($request->all());
        return back()->with('success', 'Item updated.');
    }
    public function destroy($id) {
        Item::destroy($id);
        return back()->with('success', 'Item deleted.');
    }
}