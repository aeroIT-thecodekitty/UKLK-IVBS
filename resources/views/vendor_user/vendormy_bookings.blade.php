<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrows - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    @include('layouts.user-navbar')

    <div class="flex flex-1 relative">
        
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-black text-[#0B318F] tracking-wide">My Borrowed Items</h2>
                <p class="text-xs text-gray-500 mt-1">Track equipment rentals, request quantities, execution windows, and active return handovers.</p>
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
                                <th class="p-4">Borrow ID</th>
                                <th class="p-4">Item Details</th>
                                <th class="p-4">Qty</th>
                                <th class="p-4">Borrowing Window (Start / End)</th>
                                <th class="p-4">Return Progress</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Actions Block</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            @forelse($borrows as $borrow)
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    
                                    <td class="p-4 font-mono font-bold text-[#0B318F]">
                                        #{{ $borrow->id }}
                                    </td>
                                    
                                    <td class="p-4 font-semibold text-slate-900">
                                        {{ $borrow->item_name ?? 'Equipment Unit' }}
                                        <div class="text-xs text-gray-400 font-normal mt-0.5">
                                            Item Reference: ID-{{ $borrow->item_id }}
                                        </div>
                                    </td>

                                    <td class="p-4 font-bold text-slate-700">
                                        {{ $borrow->quantity_requested }}x
                                    </td>
                                    
                                    <td class="p-4 text-xs font-medium">
                                        <div class="text-slate-800 font-semibold">Start: {{ $borrow->start_time }}</div>
                                        <div class="text-gray-400">End: {{ $borrow->end_time }}</div>
                                    </td>
                                    
                                    <td class="p-4 text-xs">
                                        @if($borrow->returned_at)
                                            <div class="text-green-700 font-semibold">Handed Over:</div>
                                            <div class="text-gray-500 font-mono">{{ $borrow->returned_at }}</div>
                                            @if($borrow->return_condition)
                                                <div class="text-[10px] text-amber-800 italic mt-0.5">Cond: "{{ $borrow->return_condition }}"</div>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic font-medium">In Possession / Outstanding</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4">
                                        @php 
                                            $statusLower = strtolower($borrow->status); 
                                        @endphp
                                        
                                        @if($statusLower === 'approved' || $statusLower === 'confirmed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full">
                                                ● Active Allocation
                                            </span>
                                        @elseif($statusLower === 'returned')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full">
                                                ● Returned
                                            </span>
                                        @elseif($statusLower === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-full">
                                                ● Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-full">
                                                ● {{ ucfirst($borrow->status ?? 'Rejected') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-center">
                                        @if($statusLower === 'approved' || $statusLower === 'confirmed')
                                            <form method="POST" action="{{ route('student.borrow_equipment.return', $borrow->id) }}" 
                                                  onsubmit="return confirm('Confirm that you have manually dropped off this item back to the inventory deck?');">
                                                @csrf
                                                <button type="submit" class="bg-[#0B318F] hover:bg-[#072266] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow transition cursor-pointer">
                                                    Mark as Returned
                                                </button>
                                            </form>
                                        @elseif($statusLower === 'returned')
                                            <span class="text-xs text-green-600 font-bold italic">Awaiting Admin Signoff</span>
                                        @elseif($statusLower === 'pending')
                                            <span class="text-xs text-gray-400 italic">Locked</span>
                                        @else
                                            <span class="text-xs text-gray-400 font-bold select-none">-</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-12 text-center text-gray-400 text-sm font-medium italic bg-slate-100/50">
                                        <div class="text-2xl mb-2">📦</div>
                                        No active equipment rentals or item borrowings tracked inside your profile records yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>