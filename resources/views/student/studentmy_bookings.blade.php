<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" 
      x-data="{ sidebarOpen: true, paymentModal: false, payId: '', payAmount: '', payType: '', payTable: '' }">

    @include('layouts.user-navbar')

    <div class="flex flex-1 relative">
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-black text-[#0B318F] tracking-wide">My Bookings</h2>
                <p class="text-xs text-gray-500 mt-1">Review and submit bank transaction receipts.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 text-xs font-bold px-4 py-3 rounded-xl max-w-4xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden max-w-6xl">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0B318F] text-white text-xs font-bold uppercase tracking-wider">
                                <th class="p-4">Booking ID</th>
                                <th class="p-4">Category Type</th>
                                <th class="p-4">Schedule Details</th>
                                <th class="p-4">Price</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Results</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    
                                    <td class="p-4 font-mono font-bold text-[#0B318F]">#{{ $booking->id }}</td>
                                    
                                    <td class="p-4">
                                        @if(isset($booking->stall_id))
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Stall Plot</span>
                                        @elseif(isset($booking->cabin_id))
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Cibo Cabin</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Venue Lounge</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-xs font-medium">
                                        @if(isset($booking->start_time))
                                            <div>Start: {{ $booking->start_time }}</div>
                                            <div class="text-gray-400">End: {{ $booking->end_time }}</div>
                                        @else
                                            <div>Date: {{ $booking->booking_date }}</div>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 font-bold text-slate-900">
                                        {{ isset($booking->amount) ? 'RM '.number_format($booking->amount, 2) : 'Free' }}
                                    </td>
                                    
                                    <td class="p-4">
                                        @if($booking->status === 'pending' || $booking->status === 'pending_approval')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 rounded-full">
                                                ⏳ Stage 1: Verifying Slot
                                            </span>
                                        @elseif($booking->status === 'awaiting_payment')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full">
                                                💵 Stage 2: Approved, Pay Now
                                            </span>
                                        @elseif($booking->status === 'pending_payment_verification')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-full">
                                                🔍 Stage 3: Verifying Payment
                                            </span>
                                        @elseif($booking->status === 'payment_confirmed' || $booking->status === 'confirmed' || $booking->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full">
                                                ✅ Verified & Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-full">
                                                ❌ Cancelled / Rejected
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        @if($booking->status === 'pending' || $booking->status === 'pending_approval')
                                            <span class="text-xs text-gray-400 italic">Locked until admin validates slot</span>
                                        
                                        @elseif($booking->status === 'awaiting_payment')
                                            <button @click="paymentModal = true; 
                                                            payId = '{{ $booking->id }}'; 
                                                            payAmount = '{{ number_format($booking->amount, 2) }}';
                                                            payType = '{{ isset($booking->stall_id) ? 'Stall Plot' : 'Cibo Cabin' }}';
                                                            payTable = '{{ isset($booking->stall_id) ? 'stall_bookings' : 'cabin_bookings' }}';" 
                                                    class="bg-[#0B318F] hover:bg-[#072266] text-white text-xs font-bold px-4 py-2 rounded-full shadow transition cursor-pointer">
                                                Proceed to Pay
                                            </button>

                                        @elseif($booking->status === 'pending_payment_verification')
                                            <span class="text-xs text-amber-600 font-semibold italic">Receipt processing...</span>
                                        @elseif($booking->status === 'payment_confirmed' || $booking->status === 'confirmed' || $booking->status === 'approved')
                                            <span class="text-xs text-green-600 font-bold">Success!</span>
                                        @else
                                            <span class="text-xs text-gray-400 select-none">-</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-gray-400 text-sm font-medium italic bg-slate-100/50">
                                        No entries tracked inside your personal record profile yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <div x-show="paymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="paymentModal = false" class="bg-white rounded-[2.5rem] max-w-md w-full p-8 shadow-2xl border border-gray-100 space-y-5 transform transition-all">
            
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-xl font-black text-[#0B318F]">Manual Bank Transfer</h3>
                <p class="text-xs text-gray-500">Please complete your manual transaction to the account details below.</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Item Category</div>
                    <div class="text-sm font-bold text-slate-800" x-text="payType"></div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Amount Owed</div>
                    <div class="text-base font-black text-[#9E007E]">RM <span x-text="payAmount"></span></div>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-200/60 rounded-2xl p-5 space-y-2 text-xs">
                <div class="text-xs font-bold text-[#0B318F] uppercase tracking-wide mb-1">🏦 Recipient Account Details:</div>
                <div class="flex justify-between"><span class="text-gray-500 font-medium">Bank Name:</span> <span class="font-bold text-slate-800">Bank Islam Malaysia Berhad</span></div>
                <div class="flex justify-between"><span class="text-gray-500 font-medium">Account Name:</span> <span class="font-bold text-slate-800">Bendahari UKLK</span></div>
                <div class="flex justify-between"><span class="text-gray-500 font-medium">Account Number:</span> <span class="font-mono font-bold text-md text-[#9E007E] select-all bg-white px-1.5 py-0.5 rounded border border-purple-100">0000000000</span></div>
                <div class="flex justify-between pt-1 border-t border-blue-200/40"><span class="text-gray-500 font-medium">Payment Reference:</span> <span class="font-mono font-bold text-slate-700">UKLK-<span x-text="payId"></span></span></div>
            </div>

            <form method="POST" :action="'{{ url('student/upload-receipt') }}/' + payId" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="table_type" :value="payTable">
                
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1.5">Upload Receipt Proof (.png, .jpg, .jpeg)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50 text-center hover:bg-slate-50 transition relative">
                        <input type="file" name="receipt" required class="absolute inset-0 opacity-0 w-full h-full cursor-pointer">
                        <div class="text-xs text-gray-500 font-medium pointer-events-none">
                            📸 Click here or drag file to attach transfer statement receipt
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="paymentModal = false" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-full text-xs uppercase tracking-wider transition">Cancel</button>
                    <button type="submit" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-full text-xs uppercase tracking-wider transition shadow">Submit Receipt</button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>