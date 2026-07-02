<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'admin') {
            // Admin stats
            $data['total_students'] = User::where('role', 'student')->count();
            $data['total_vendors'] = User::where('role', 'vendor')->count();
            $data['pending_lounge'] = Reservation::where('status', 'pending')->whereHas('resource', function($q){ $q->where('type', 'venue'); })->count();
            $data['pending_cibo'] = Reservation::where('status', 'pending')->whereHas('resource', function($q){ $q->where('type', 'vending_space'); })->count();
            $data['pending_items'] = Reservation::where('status', 'pending')->whereHas('resource', function($q){ $q->where('type', 'item'); })->count();
            
            // Recent applications for admin approval
            $data['recent_reservations'] = Reservation::with(['user', 'resource'])->latest()->take(10)->get();
        } else {
            // Student/Vendor view data
            $data['my_reservations'] = Reservation::with('resource')->where('user_id', $user->id)->latest()->get();
            
            // Available items for forms
            $data['available_lounges'] = Resource::where('type', 'venue')->where('status', 'available')->get();
            $data['available_items'] = Resource::where('type', 'item')->where('status', 'available')->get();
            $data['available_cibo'] = Resource::where('type', 'vending_space')->where('status', 'available')->get();
        }

        return view('dashboard', $data);
    }
}