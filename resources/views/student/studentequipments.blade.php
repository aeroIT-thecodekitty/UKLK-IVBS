<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Catalogue - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: true, equipmentModal: false, activeItemId: '', activeItemName: '' }">

    @include('layouts.user-navbar')

    <div class="flex flex-1 relative">
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-black text-[#0B318F] tracking-wide">Equipment & Items Inventory</h2>
                <p class="text-xs text-gray-500 mt-1">Browse campus multimedia logistics, tools, and technical equipment available for short-term borrowing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl">
                @forelse($items as $item)
                    @php
                        $statusLower = strtolower($item->status);
                        $isAvailable = ($statusLower === 'available');
                    @endphp

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between transition duration-200 hover:shadow-md">
                        <div class="relative h-40 bg-slate-100 flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#0B318F]/10 to-[#1B49A6]/20 flex items-center justify-center">
                                <span class="text-4xl select-none">📦</span>
                            </div>

                            <div class="absolute top-4 right-4">
                                @if($isAvailable && $item->total_quantity > 0)
                                    <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full shadow-sm flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Available
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-full shadow-sm flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-[#0B318F] tracking-tight leading-snug">{{ $item->name }}</h3>
                                <div class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                    <span>🔢</span> Total Quantity: <span class="text-slate-800 font-bold ml-0.5">{{ $item->total_quantity }} units</span>
                                </div>
                                <p class="text-xs text-gray-600 font-medium leading-relaxed pt-1">
                                    {{ $item->description ?? 'No specific technical parameters logged for this inventory asset yet.' }}
                                </p>
                            </div>

                            <div class="pt-3">
                                @if($isAvailable && $item->total_quantity > 0)
                                    <button @click="equipmentModal = true; activeItemId = '{{ $item->id }}'; activeItemName = '{{ $item->name }}'" 
                                            class="w-full bg-[#0B318F] hover:bg-[#072266] text-white font-bold py-2.5 px-4 rounded-xl text-xs text-center tracking-wider uppercase transition cursor-pointer">
                                        Request Borrowing
                                    </button>
                                @else
                                    <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-2.5 px-4 rounded-xl text-xs text-center tracking-wider uppercase cursor-not-allowed border border-gray-300">
                                        Unavailable
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-slate-100/50 rounded-2xl border border-gray-200 p-12 text-center text-gray-400 font-medium italic">
                        <div class="text-3xl mb-2">📦</div> No hardware assets or inventory items found.
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div x-show="equipmentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="equipmentModal = false" class="bg-white rounded-[2rem] max-w-md w-full p-8 shadow-2xl border border-gray-100 space-y-4">
            <div>
                <h3 class="text-xl font-black text-[#0B318F] truncate" x-text="activeItemName"></h3>
                <p class="text-xs text-gray-500">Submit a logistics borrow application form below.</p>
            </div>
            <form method="POST" action="{{ route('student.borrow_equipment.store') }}" class="space-y-4 text-sm">
                @csrf
                <input type="hidden" name="item_id" :value="activeItemId">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Quantity Requested</label>
                    <input type="number" name="quantity_requested" required min="1" value="1" class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Borrow Start Window</label>
                    <input type="datetime-local" name="start_time" required class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Return Due Window</label>
                    <input type="datetime-local" name="end_time" required class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Reason for Borrowing</label>
                    <textarea name="reason" rows="2" placeholder="Purpose statement details..." class="w-full bg-gray-50 border border-gray-300 rounded-2xl py-2 px-4 focus:outline-none focus:border-purple-600 text-xs resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="equipmentModal = false" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-full text-xs uppercase tracking-wider transition">Cancel</button>
                    <button type="submit" class="w-1/2 bg-[#9E007E] hover:bg-[#800066] text-white font-bold py-2.5 rounded-full text-xs uppercase tracking-wider transition shadow">Confirm Borrow</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>