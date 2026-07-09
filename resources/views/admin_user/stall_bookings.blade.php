<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Stall Booking Manager') }}
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
                        Pending Queue ({{ $pendingBookings->count() }})
                    </button>
                    <button @click="currentTab = 'confirmed'" :class="currentTab === 'confirmed' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'" class="flex-1 py-2.5 px-4 rounded-lg font-medium transition text-center cursor-pointer">
                        Confirmed Active ({{ $confirmedBookings->count() }})
                    </button>
                    <button @click="currentTab = 'past'" :class="currentTab === 'past' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'" class="flex-1 py-2.5 px-4 rounded-lg font-medium transition text-center cursor-pointer">
                        Past History ({{ $pastBookings->count() }})
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-purple-950 text-purple-200 text-sm">
                                <th class="p-4">Stall No</th>
                                <th class="p-4">Applicant Detail</th>
                                <th class="p-4">Reserved Date</th>
                                <th class="p-4">Status State</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        
                        <!-- PENDING SUBMISSION QUEUE TAB -->
                        <tbody x-show="currentTab === 'pending'" class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($pendingBookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">
                                        Stall #{{ $booking->stallParking->stall_number ?? $booking->stall_id }}
                                    </td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $booking->full_name }}</div>
                                        <div class="text-xs text-gray-400 opacity-80">{{ $booking->company }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">{{ $booking->booking_date }}</td>
                                    
                                    <td class="p-4">
                                        @if($booking->status === 'pending' || $booking->status === 'pending_approval')
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-orange-100 text-orange-800 border-orange-300 border rounded-full">
                                                Reviewing Slot
                                            </span>
                                        @elseif($booking->status === 'awaiting_payment')
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 border-blue-300 border rounded-full">
                                                Awaiting Pay
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-800 border-amber-300 border rounded-full">
                                                Verify Receipt
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-right flex justify-end items-center gap-2">
                                        <!-- ACTION STATE A: Initial Slot Validation -->
                                        @if($booking->status === 'pending' || $booking->status === 'pending_approval')
                                            <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                                @csrf 
                                                <input type="hidden" name="status" value="awaiting_payment">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-xs font-bold transition cursor-pointer shadow-sm">
                                                    Verify Slot (Allow Pay)
                                                </button>
                                            </form>
                                        
                                        <!-- ACTION STATE B: User Hasn't Uploaded Yet -->
                                        @elseif($booking->status === 'awaiting_payment')
                                            <span class="text-xs text-gray-400 italic mr-2">Waiting for student checkout...</span>
                                            <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                                @csrf 
                                                <input type="hidden" name="status" value="payment_confirmed">
                                                <button type="submit" class="bg-purple-900 hover:bg-purple-800 text-white px-2.5 py-1.5 rounded-md text-xs font-medium transition cursor-pointer">Force Confirm</button>
                                            </form>

                                        <!-- ACTION STATE C: Financial Receipt Verification -->
                                        @elseif($booking->status === 'pending_payment_verification')
                                            @if($booking->receipt_url)
                                                <a href="{{ asset($booking->receipt_url) }}" target="_blank" class="mr-2 inline-block border border-gray-200 dark:border-gray-600 rounded-lg p-1 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 transition" title="View Full Receipt">
                                                    <img src="{{ asset($booking->receipt_url) }}" alt="Receipt" class="h-8 w-8 object-cover rounded-md">
                                                </a>
                                            @endif

                                            <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                                @csrf 
                                                <input type="hidden" name="status" value="payment_confirmed">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-xs font-medium cursor-pointer transition shadow-sm">Approve Payment</button>
                                            </form>

                                            <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                                @csrf 
                                                <input type="hidden" name="status" value="awaiting_payment">
                                                <button type="submit" class="border border-red-200 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-950/30 px-3 py-1.5 rounded-md text-xs font-medium cursor-pointer transition">Reject Receipt</button>
                                            </form>
                                        @endif

                                        <!-- Global Cancellation Anchor -->
                                        <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="border border-gray-300 text-gray-500 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 p-1.5 rounded-md text-xs font-medium transition" title="Reject entirely">
                                                Cancel
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">All caught up! No requests pending review.</td></tr>
                            @endforelse
                        </tbody>

                        <!-- CONFIRMED SUBMISSIONS TAB -->
                        <tbody x-show="currentTab === 'confirmed'" class="divide-y divide-gray-100 dark:divide-gray-700" x-cloak>
                            @forelse($confirmedBookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">Stall #{{ $booking->stallParking->stall_number ?? $booking->stall_id }}</td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $booking->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $booking->company }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">{{ $booking->booking_date }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 border border-green-300 rounded-full">Confirmed Active</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('admin_user.stall-bookings.update', $booking->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="status" value="past">
                                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium cursor-pointer transition">Archive Record</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">No active confirmed parking sessions.</td></tr>
                            @endforelse
                        </tbody>

                        <!-- ARCHIVED LOGS TAB -->
                        <tbody x-show="currentTab === 'past'" class="divide-y divide-gray-100 dark:divide-gray-700" x-cloak>
                            @forelse($pastBookings as $booking)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition">
                                    <td class="p-4 font-bold text-purple-700 dark:text-purple-400">Stall #{{ $booking->stallParking->stall_number ?? $booking->stall_id }}</td>
                                    <td class="p-4 text-sm">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $booking->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $booking->company }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-medium">{{ $booking->booking_date }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border rounded-full">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-xs text-gray-400 italic font-medium pr-6">Data Entry Locked</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">Historical archive log is empty.</td></tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>