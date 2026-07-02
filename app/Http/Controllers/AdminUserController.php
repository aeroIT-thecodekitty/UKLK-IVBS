<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    /**
     * Display combined lists of Web and Supabase App users.
     */
    public function index()
    {
        // 1. Fetch Web Users from public schema
        $webUsers = DB::table('users')->orderBy('created_at', 'desc')->get();
        
        // 2. Fetch App Users directly from Supabase internal auth schema
        // Uses the ->> operator to extract 'name' from the raw_user_meta_data JSON column
        $appUsers = DB::table('auth.users')
            ->select([
                'id',
                'email',
                'phone',
                'created_at',
                DB::raw("raw_user_meta_data->>'name' as name")
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_user.users_manager', compact('webUsers', 'appUsers'));
    }

    // ==========================================
    // WEB USERS ACTIONS (Standard public table)
    // ==========================================
    public function storeWebUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'role' => 'required|string'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Web account registered successfully.');
    }

    public function updateWebUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return back()->with('success', 'Web account details updated.');
    }

    public function destroyWebUser($id)
{
    // 1. Clear out all dependent child rows first to satisfy foreign key constraints
    DB::table('stall_bookings')->where('userid', $id)->delete();
    DB::table('cabin_bookings')->where('userid', $id)->delete();
    DB::table('item_bookings')->where('userid', $id)->delete();
    DB::table('venue_bookings')->where('userid', $id)->delete();

    // 2. Now that the history is clear, delete the user safely
    DB::table('users')->where('id', $id)->delete();

    return back()->with('success', 'Web user profile and all associated booking history removed.');
}
    // ==========================================
    // SUPABASE APP USERS CRUD (auth.users schema)
    // ==========================================
    public function storeAppUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:auth.users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string'
        ]);

        // Manually provisioning a Supabase GoTrue Auth row structure via DB link
        DB::table('auth.users')->insert([
            'id' => Str::uuid()->toString(), // Supabase uses random UUID strings
            'instance_id' => '00000000-0000-0000-0000-000000000000',
            'aud' => 'authenticated',
            'role' => 'authenticated',
            'email' => $request->email,
            'encrypted_password' => bcrypt($request->password), // Supabase internal auth uses bcrypt
            'email_confirmed_at' => now(),
            'phone' => $request->phone,
            'raw_app_meta_data' => json_encode(['provider' => 'email', 'providers' => ['email']]),
            'raw_user_meta_data' => json_encode(['name' => $request->name]), // Injects name to metadata
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'App user registered into Supabase Authentication engine.');
    }

    public function updateAppUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:auth.users,email,' . $id,
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:6'
        ]);

        $updateData = [
            'email' => $request->email,
            'phone' => $request->phone,
            'raw_user_meta_data' => json_encode(['name' => $request->name]),
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['encrypted_password'] = bcrypt($request->password);
        }

        DB::table('auth.users')->where('id', $id)->update($updateData);

        return back()->with('success', 'Supabase mobile auth profile updated.');
    }

    public function destroyAppUser($id)
    {
        DB::table('auth.users')->where('id', $id)->delete();
        return back()->with('success', 'User dropped from Supabase core auth layers.');
    }
}