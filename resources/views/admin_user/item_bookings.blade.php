<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Equipment Booking Manager') }}
                </h2>
                @if(session('success'))
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-4 py-1.5 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </span>
                @endif
            </div>

            <div x-data="{ currentTab: 'pending' }" class="space-y-4">
                
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm gap-2">
                    <button @click="currentTab = 'pending'" :class="currentTab === 'pending' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'" class="flex-1 py-2.5 px-4 rounded-lg font-medium transition text-center cursor-pointer">
                        Pending Approval ({{ $pendingBookings->count() }})
                    </button>
                    <button @click="currentTab = 'confirmed'" :class="currentTab === 'confirmed' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'" class="flex-1 py-2.5 px-4 rounded-lg font-medium transition text-center cursor-pointer">
                        Currently Borrowed ({{ $confirmedBookings->count() }})
                    </button>
                    <button @click="currentTab = 'past'" :class="currentTab === 'past' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'" class="flex-1 py-2.5 px-4 rounded-lg font-medium transition text-center cursor-pointer">
                        Returned/Past ({{ $pastBookings->count() }})
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-purple-950 text-purple-200 text-sm">
                                <th class="p-4">Item Requested</th>
                                <th class="p-4">Booking Details</th>
                                <th class="p-4">Date & Time Range</th>
                                <th class="p-4">Status State</th>
                                <th class="p-4 text-right">Actions / Verification Logs</th>
                            </tr>
                        </thead>
                        
                        <tbody x-show="currentTab === 'pending'" class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($pendingBookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">{{ $booking->item->name ?? 'Item ID: ' . $booking->item_id }}</td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">Qty: {{ $booking->quantity_requested }}</div>
                                        <div class="text-xs text-gray-400 opacity-80">Reason: {{ $booking->reason ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">
                                        <div class="text-green-600 dark:text-green-400">Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, h:i A') }}</div>
                                        <div class="text-red-500 dark:text-red-400">End: {{ \Carbon\Carbon::parse($booking->end_time)->format('M d, h:i A') }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-300 rounded-full">
                                            Pending Admin
                                        </span>
                                    </td>
                                    <td class="p-4 text-right flex justify-end items-center gap-2">
                                        <form action="{{ route('admin_user.item-bookings.update', $booking->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-xs font-medium cursor-pointer transition shadow-sm">Approve</button>
                                        </form>

                                        <form action="{{ route('admin_user.item-bookings.update', $booking->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="border border-red-200 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-950/30 px-3 py-1.5 rounded-md text-xs font-medium cursor-pointer transition">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">All caught up! No pending equipment requests.</td></tr>
                            @endforelse
                        </tbody>

                        <tbody x-show="currentTab === 'confirmed'" class="divide-y divide-gray-100 dark:divide-gray-700" x-cloak style="display: none;">
                            @forelse($confirmedBookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">{{ $booking->item->name ?? 'Item ID: ' . $booking->item_id }}</td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">Qty: {{ $booking->quantity_requested }}</div>
                                        <div class="text-xs text-gray-400">Reason: {{ $booking->reason ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">
                                        <div class="text-green-600 dark:text-green-400">Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, h:i A') }}</div>
                                        <div class="text-red-500 dark:text-red-400">End: {{ \Carbon\Carbon::parse($booking->end_time)->format('M d, h:i A') }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300 rounded-full animate-pulse">
                                            In Possession
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('admin_user.item-bookings.update', $booking->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="status" value="returned">
                                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium cursor-pointer transition">Force Return</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">No active equipment loans right now.</td></tr>
                            @endforelse
                        </tbody>

                        <tbody x-show="currentTab === 'past'" class="divide-y divide-gray-100 dark:divide-gray-700" x-cloak style="display: none;">
                            @forelse($pastBookings as $booking)
                                @php $statusLower = strtolower($booking->status); @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">{{ $booking->item->name ?? 'Item ID: ' . $booking->item_id }}</td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">Qty: {{ $booking->quantity_requested }}</div>
                                        <div class="text-xs text-gray-400">Reason: {{ $booking->reason ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">
                                        <div class="text-gray-500">Scheduled Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, h:i A') }}</div>
                                        @if(isset($booking->returned_at))
                                            <div class="text-xs text-green-600 font-bold mt-1">Returned At: {{ $booking->returned_at }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        @if($statusLower === 'returned')
                                            <span class="px-2.5 py-1 text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 rounded-full animate-pulse">
                                                🔍 Awaiting Evaluation
                                            </span>
                                        @elseif($statusLower === 'completed' || $statusLower === 'approved')
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 border border-green-200 rounded-full">
                                                Completed Safe
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200 rounded-full">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-right max-w-xs">
                                        @if($statusLower === 'returned')
                                            <form action="{{ route('admin_user.item-bookings.update', $booking->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                                @csrf 
                                                <input type="hidden" name="status" value="completed">
                                                <input type="text" name="return_condition" placeholder="Log physical condition..." required 
                                                       class="bg-gray-50 border border-gray-300 text-xs rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-purple-600 w-44 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <button type="submit" class="bg-purple-950 hover:bg-purple-900 text-white font-bold px-2.5 py-1 rounded-md text-[11px] tracking-wide transition shadow">
                                                    Verify Return
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic font-medium pr-4">
                                                {{ $booking->return_condition ? 'Cond: "'.$booking->return_condition.'"' : 'Data Locked' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">Historical archive is empty.</td></tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>