<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Compile real-time metrics across all system tables.
     */
    public function index()
    {
        $now = Carbon::now();

        // 1. FINANCIAL REVENUE AGGREGATIONS
        $stallRevenue = DB::table('stall_bookings')->where('status', 'payment_confirmed')->sum('amount') ?? 0;
        $cabinRevenue = DB::table('cabin_bookings')->where('status', 'payment_confirmed')->sum('amount') ?? 0;
        $totalRevenue = $stallRevenue + $cabinRevenue;

        // 2. REGISTRATION ACCOUNT VOLUMES
        $totalWebUsers = DB::table('users')->count();
        $totalAppUsers = DB::table('auth.users')->count();

        // 3. LOGISTICS QUEUE PENETRATIONS
        $pendingStalls = DB::table('stall_bookings')->whereIn('status', ['pending', 'pending_approval', 'pending_payment_verification'])->count();
        $pendingCabins = DB::table('cabin_bookings')->whereIn('status', ['pending', 'pending_approval', 'pending_payment_verification'])->count();
        $pendingItems = DB::table('item_bookings')->where('status', 'pending')->count();
        $pendingVenues = DB::table('venue_bookings')->where('status', 'pending')->count();
        $totalPendingRequests = $pendingStalls + $pendingCabins + $pendingItems + $pendingVenues;

        // 4. ACTIVE & OVERDUE INVENTORY TRACKING
        $activeLoansCount = DB::table('item_bookings')->whereIn('status', ['approved', 'confirmed'])->count();
        
        // Fetch specific items currently marked overdue
        $overdueEquipment = DB::table('item_bookings')
            ->leftJoin('items', 'item_bookings.item_id', '=', 'items.id')
            ->leftJoin('users', 'item_bookings.userid', '=', 'users.id')
            ->whereIn('item_bookings.status', ['approved', 'confirmed'])
            ->where('item_bookings.end_time', '<', $now)
            ->select([
                'item_bookings.id',
                'item_bookings.quantity_requested',
                'item_bookings.end_time',
                'items.name as item_name',
                'users.name as student_name',
                'users.phone as student_phone'
            ])
            ->orderBy('item_bookings.end_time', 'asc')
            ->get();

        // 5. RECENT PHYSICAL INCIDENT TRACKING
        $assetIncidents = DB::table('item_bookings')
            ->leftJoin('items', 'item_bookings.item_id', '=', 'items.id')
            ->leftJoin('users', 'item_bookings.userid', '=', 'users.id')
            ->where('item_bookings.status', 'completed')
            ->whereNotNull('item_bookings.return_condition')
            ->where('item_bookings.return_condition', '!=', '')
            ->select([
                'item_bookings.id',
                'item_bookings.returned_at',
                'item_bookings.return_condition',
                'items.name as item_name',
                'users.name as student_name'
            ])
            ->orderBy('item_bookings.returned_at', 'desc')
            ->take(5)
            ->get();

        return view('admin_user.analytics', compact(
            'totalRevenue',
            'stallRevenue',
            'cabinRevenue',
            'totalWebUsers',
            'totalAppUsers',
            'totalPendingRequests',
            'activeLoansCount',
            'overdueEquipment',
            'assetIncidents'
        ));
    }
}