<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StallBookingController; 
use App\Http\Controllers\StallParkingController; 

// Booking Category Controllers
use App\Http\Controllers\CabinBookingController; 
use App\Http\Controllers\ItemBookingController;  
use App\Http\Controllers\VenueBookingController; 
use App\Http\Controllers\StudentBookingController;
use App\Http\Controllers\AdminUserController; // Added for User CRUD Management
use App\Http\Controllers\AnalyticsController; // Added for Analytics & Reports

// Inventory Management Imports
use App\Http\Controllers\CabinController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VenueController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.portal'); 
});

// Smart Dashboard Route: Routes users based on their role
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') return view('admin_user.dashboard'); 
    if ($role === 'vendor' || $role === 'vendor_user') return redirect()->route('vendor.home');
    if ($role === 'student') return view('student.studentdashboard'); 
    abort(403, 'Unauthorized role.');
})->middleware(['auth'])->name('dashboard');

// Profile & Logged-In Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ==========================================
    // SECURED ADMIN-ONLY GROUP
    // ==========================================
    Route::middleware('admin')->prefix('admin')->group(function () {        
        Route::get('/stall-bookings', [StallBookingController::class, 'stall_bookings'])->name('admin_user.stall_bookings');
        Route::get('/stall-parking', [StallParkingController::class, 'stall_parking'])->name('admin_user.stall_parking');
        Route::post('/stall-bookings/{id}/update', [StallBookingController::class, 'updateStatus'])->name('admin_user.stall-bookings.update');

        Route::get('/cabin-bookings', [CabinBookingController::class, 'booking'])->name('admin_user.cabin_bookings');
        Route::post('/cabin-bookings/{id}/update', [CabinBookingController::class, 'updateStatus'])->name('admin_user.cabin-bookings.update');

        Route::get('/item-bookings', [ItemBookingController::class, 'booking'])->name('admin_user.item_bookings');
        Route::post('/item-bookings/{id}/update', [ItemBookingController::class, 'updateStatus'])->name('admin_user.item-bookings.update');

        Route::get('/venue-bookings', [VenueBookingController::class, 'booking'])->name('admin_user.venue_bookings');
        Route::post('/venue-bookings/{id}/update', [VenueBookingController::class, 'updateStatus'])->name('admin_user.venue-bookings.update');

        Route::get('/admin_user/stall_parking', [StallParkingController::class, 'stall_parking'])->name('admin_user.stall_parking_index');
        Route::get('/admin_user/stall_parking/create', [StallParkingController::class, 'stall_parking_create'])->name('admin_user.stall_parking_create');
        Route::post('/admin_user/stall_parking', [StallParkingController::class, 'stall_parking_store'])->name('admin_user.stall_parking_store');
        Route::get('/admin_user/stall_parking/{id}/edit', [StallParkingController::class, 'stall_parking_edit'])->name('admin_user.stall_parking_edit');
        Route::put('/admin_user/stall_parking/{id}', [StallParkingController::class, 'stall_parking_update'])->name('admin_user.stall_parking_update');
        Route::delete('/admin_user/stall_parking/{id}', [StallParkingController::class, 'stall_parking_destroy'])->name('admin_user.stall_parking_destroy');

        Route::get('/cabins', [CabinController::class, 'cabin'])->name('admin_user.cabin');
        Route::post('/cabins', [CabinController::class, 'store'])->name('admin_user.cabin.store');
        Route::put('/cabins/{id}', [CabinController::class, 'update'])->name('admin_user.cabin.update');
        Route::delete('/cabins/{id}', [CabinController::class, 'destroy'])->name('admin_user.cabin.destroy');

        Route::get('/items', [ItemController::class, 'item'])->name('admin_user.item');
        Route::post('/items', [ItemController::class, 'store'])->name('admin_user.item.store');
        Route::put('/items/{id}', [ItemController::class, 'update'])->name('admin_user.item.update');
        Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('admin_user.item.destroy');

        Route::get('/lounges', [VenueController::class, 'lounge'])->name('admin_user.lounge');
        Route::post('/lounges', [VenueController::class, 'store'])->name('admin_user.lounge.store');
        Route::put('/lounges/{id}', [VenueController::class, 'update'])->name('admin_user.lounge.update');
        Route::delete('/lounges/{id}', [VenueController::class, 'destroy'])->name('admin_user.lounge.destroy');

        // USER MANAGEMENT CRUD SYSTEM (INTEGRATED)
        Route::get('/users-manager', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/users-manager/web/store', [AdminUserController::class, 'storeWebUser'])->name('admin.users.web.store');
        Route::post('/users-manager/web/{id}/update', [AdminUserController::class, 'updateWebUser'])->name('admin.users.web.update');
        Route::delete('/users-manager/web/{id}/delete', [AdminUserController::class, 'destroyWebUser'])->name('admin.users.web.destroy');
        Route::post('/users-manager/app/store', [AdminUserController::class, 'storeAppUser'])->name('admin.users.app.store');
        Route::post('/users-manager/app/{id}/update', [AdminUserController::class, 'updateAppUser'])->name('admin.users.app.update');
        Route::delete('/users-manager/app/{id}/delete', [AdminUserController::class, 'destroyAppUser'])->name('admin.users.app.destroy');

        // OPERATIONAL ANALYTICS & REPORTS ENGINE (CORRECTED PLACEMENT)
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
    });

    // ==========================================
    // INTEGRATED VENDOR CORE GROUP
    // ==========================================
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', function () { return view('vendor_user.vendordashboard'); })->name('dashboard');
        Route::get('/home', function () { return view('vendor_user.vendordashboard'); })->name('home');
        Route::get('/profile', function () { return view('vendor_user.vendorprofile'); })->name('profile');
        
        // Vendor Historical Bookings Ledger
        Route::get('/my-bookings', function () {
            $userId = Auth::id();
            $stalls = DB::table('stall_bookings')->where('userid', $userId)->select('id', 'stall_id', 'booking_date', 'amount', 'status', 'receipt_url')->get();
            $cabins = DB::table('cabin_bookings')->where('userid', $userId)->select('id', 'cabin_id', 'booking_date', 'amount', 'status', 'receipt_url')->get();

            $bookings = collect()->concat($stalls)->concat($cabins)->sortByDesc('id');
            return view('vendor_user.my_bookings', compact('bookings'));
        })->name('my_bookings');

        // Catalogues & Inventories Accessible by Vendors
        Route::get('/stalls', function () {
            $stalls = DB::table('stall_parking')->select('id', 'stall_number', 'location', 'is_roofed', 'student_rate', 'total_slots', 'status', 'description')->orderBy('stall_number', 'asc')->get();
            return view('vendor_user.stalls', compact('stalls'));
        })->name('stalls');

        Route::get('/cibo-cabin', function () {
            $cabins = DB::table('cibo_cabin')->select('id', 'cabin_number', 'location', 'is_roofed', 'rate', 'total_slots', 'status', 'description')->orderBy('cabin_number', 'asc')->get();
            return view('vendor_user.cibo_cabin', compact('cabins'));
        })->name('cibo_cabin');
    });

    // ==========================================
    // SECURED STUDENT CORE GROUP
    // ==========================================
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', function () { return view('student.studentdashboard'); })->name('dashboard');
        Route::get('/profile', function () { return view('student.studentprofile'); })->name('profile');
        Route::get('/home', function () { return view('student.studentdashboard'); })->name('home');
        
        // Personal Historical Registries
        Route::get('/my-bookings', function () {
            $userId = Auth::id();
            $stalls = DB::table('stall_bookings')->where('userid', $userId)->select('id', 'stall_id', 'booking_date', 'amount', 'status', 'receipt_url')->get();
            $cabins = DB::table('cabin_bookings')->where('userid', $userId)->select('id', 'cabin_id', 'booking_date', 'amount', 'status', 'receipt_url')->get();
            $venues = DB::table('venue_bookings')->where('userid', $userId)->select('id', 'venue_id', 'start_time', 'end_time', 'status')->get();

            $bookings = collect()->concat($stalls)->concat($cabins)->concat($venues)->sortByDesc('id');
            return view('student.studentmy_bookings', compact('bookings'));
        })->name('my_bookings');

        // Performs an inner join to match item_id references and load real equipment titles
        Route::get('/my-borrows', function () {
            $userId = Auth::id();
            $borrows = DB::table('item_bookings')
                ->leftJoin('items', 'item_bookings.item_id', '=', 'items.id')
                ->where('item_bookings.userid', $userId)
                ->select('item_bookings.*', 'items.name as item_name')
                ->orderByDesc('item_bookings.id')
                ->get();
            return view('student.studentmy_borrows', compact('borrows'));
        })->name('my_borrows');
        
        // Inventories & Catalogues
        Route::get('/student-lounge', function () {
            $venues = DB::table('venues')->select('id', 'name', 'location', 'capacity', 'status', 'description')->orderBy('name', 'asc')->get();
            return view('student.student_lounge', compact('venues'));
        })->name('student_lounge');

        Route::get('/stalls', function () {
            $stalls = DB::table('stall_parking')->select('id', 'stall_number', 'location', 'is_roofed', 'student_rate', 'total_slots', 'status', 'description')->orderBy('stall_number', 'asc')->get();
            return view('student.studentstalls', compact('stalls'));
        })->name('stalls');

        Route::get('/equipments', function () {
            $items = DB::table('items')->select('id', 'name', 'total_quantity', 'status', 'description')->orderBy('name', 'asc')->get();
            return view('student.studentequipments', compact('items'));
        })->name('equipments');

        Route::get('/cibo-cabin', function () {
            $cabins = DB::table('cibo_cabin')->select('id', 'cabin_number', 'location', 'is_roofed', 'rate', 'total_slots', 'status', 'description')->orderBy('cabin_number', 'asc')->get();
            return view('student.studentcibo_cabin', compact('cabins'));
        })->name('cibo_cabin');

        // ==========================================
        // SUBMISSION STORAGE HOOKS (POST)
        // ==========================================
        Route::post('/book-stall', function (Request $request) {
            $request->validate([
                'stall_id' => 'required|integer',
                'booking_date' => 'required|date',
                'company' => 'nullable|string|max:255',
                'full_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:50',
            ]);
            $stallPrice = DB::table('stall_parking')->where('id', $request->stall_id)->value('student_rate') ?? 0;
            DB::table('stall_bookings')->insert([
                'stall_id' => $request->stall_id,
                'booking_date' => $request->booking_date,
                'company' => $request->company,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'status' => 'pending', 
                'amount' => $stallPrice,
                'userid' => Auth::id(),
                'created_at' => now(),
            ]);
            return redirect()->route('student.my_bookings')->with('success', 'Stall requested! Waiting for admin slot verification.');
        })->name('book_stall.store');

        Route::post('/book-cabin', function (Request $request) {
            $request->validate([
                'cabin_id' => 'required|integer',
                'booking_date' => 'required|date',
                'company' => 'nullable|string|max:255',
                'full_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:50',
            ]);
            $cabinPrice = DB::table('cibo_cabin')->where('id', $request->cabin_id)->value('rate') ?? 0;
            DB::table('cabin_bookings')->insert([
                'cabin_id' => $request->cabin_id,
                'booking_date' => $request->booking_date,
                'company' => $request->company,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'status' => 'pending', 
                'amount' => $cabinPrice,
                'userid' => Auth::id(),
                'created_at' => now(),
            ]);
            return redirect()->route('student.my_bookings')->with('success', 'Cibo Cabin space requested! Waiting for admin validation.');
        })->name('book_cabin.store');

        Route::post('/book-venue', function (Request $request) {
            $request->validate([
                'venue_id' => 'required|integer',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'reason' => 'nullable|string',
            ]);
            DB::table('venue_bookings')->insert([
                'venue_id' => $request->venue_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => $request->reason,
                'status' => 'pending',
                'userid' => Auth::id(),
            ]);
            return redirect()->route('student.my_bookings')->with('success', 'Lounge reservation request submitted successfully!');
        })->name('book_venue.store');

        Route::post('/borrow-equipment', function (Request $request) {
            $request->validate([
                'item_id' => 'required|integer',
                'quantity_requested' => 'required|integer|min:1',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'reason' => 'nullable|string',
            ]);
            DB::table('item_bookings')->insert([
                'item_id' => $request->item_id,
                'quantity_requested' => $request->quantity_requested,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => $request->reason,
                'status' => 'pending',
                'userid' => Auth::id(),
                'created_at' => now(),
            ]);
            return redirect()->route('student.my_borrows')->with('success', 'Equipment borrowing request submitted successfully!');
        })->name('borrow_equipment.store');

        // POST PIPELINE FOR SUBMITTING ASSET RETURNS (Bypasses Eloquent model updated_at columns)
        Route::post('/return-equipment/{id}', function ($id) {
            DB::table('item_bookings')
                ->where('id', $id)
                ->where('userid', Auth::id())
                ->update([
                    'status' => 'returned',
                    'returned_at' => now()
                ]);

            return redirect()->route('student.my_borrows')->with('success', 'Asset successfully marked as returned! Awaiting admin evaluation confirmation.');
        })->name('borrow_equipment.return');

        // RECEIPT UPLOAD PIPELINE
        Route::post('/upload-receipt/{id}', function (Request $request, $id) {
            $request->validate([
                'receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'table_type' => 'required|string'
            ]);

            $file = $request->file('receipt');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/receipts'), $filename);
            $savedUrl = 'uploads/receipts/' . $filename;

            DB::table($request->table_type)->where('id', $id)->where('userid', Auth::id())->update([
                'receipt_url' => $savedUrl,
                'status' => 'pending_payment_verification' 
            ]);

            return redirect()->route('student.my_bookings')->with('success', 'Payment receipt uploaded successfully! Waiting for financial confirmation.');
        })->name('upload_receipt');
    });
});

require __DIR__.'/auth.php';