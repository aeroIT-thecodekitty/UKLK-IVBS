<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stalls Catalogue - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: true, stallModal: false, activeStallId: '', activeStallNum: '', dateError: '' }">

    @include('layouts.user-navbar')

    <div class="flex flex-1 relative">
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-black text-[#0B318F] tracking-wide">Stall & Parking Catalogue</h2>
                <p class="text-xs text-gray-500 mt-1">Browse stall parkings, roofed and unroofed.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl">
                @forelse($stalls as $stall)
                    @php
                        $statusLower = strtolower($stall->status);
                        $isAvailable = ($statusLower === 'available');
                    @endphp

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between transition duration-200 hover:shadow-md">
                        <div class="relative h-40 bg-slate-100 flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#0B318F]/10 to-[#1B49A6]/20 flex items-center justify-center">
                                <span class="text-4xl select-none">🏪</span>
                            </div>
                            
                            <div class="absolute top-4 left-4">
                                @if($stall->is_roofed)
                                    <span class="px-2.5 py-1 text-[10px] font-bold text-blue-800 bg-blue-100 border border-blue-200 rounded-md uppercase tracking-wide">☂️ Roofed</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold text-amber-800 bg-amber-100 border border-amber-200 rounded-md uppercase tracking-wide">☀️ Unroofed</span>
                                @endif
                            </div>

                            <div class="absolute top-4 right-4">
                                @if($isAvailable)
                                    <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full shadow-sm flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Available
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-full shadow-sm flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Occupied
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-baseline justify-between gap-2">
                                    <h3 class="text-lg font-bold text-[#0B318F] tracking-tight">Stall Space #{{ $stall->stall_number }}</h3>
                                    <span class="text-sm font-black text-[#9E007E]">RM {{ number_format($stall->student_rate, 2) }}</span>
                                </div>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs font-semibold text-gray-500">
                                    <div class="flex items-center gap-1"><span>📍</span> {{ $stall->location }}</div>
                                    <div class="flex items-center gap-1"><span>📦</span> Capacity: {{ $stall->total_slots }} Slots</div>
                                </div>
                                <p class="text-xs text-gray-600 font-medium leading-relaxed pt-1">
                                    {{ $stall->description ?? 'No description available yet.' }}
                                </p>
                            </div>

                            <div class="pt-3">
                                @if($isAvailable)
                                    <button @click="stallModal = true; dateError = ''; activeStallId = '{{ $stall->id }}'; activeStallNum = '{{ $stall->stall_number }}'" 
                                            class="w-full bg-[#0B318F] hover:bg-[#072266] text-white font-bold py-2.5 px-4 rounded-xl text-xs text-center tracking-wider uppercase transition cursor-pointer">
                                        Rent Stall Plot
                                    </button>
                                @else
                                    <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-2.5 px-4 rounded-xl text-xs text-center tracking-wider uppercase cursor-not-allowed border border-gray-300">
                                        Currently Unavailable
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-slate-100/50 rounded-2xl border border-gray-200 p-12 text-center text-gray-400 font-medium italic">
                        <div class="text-3xl mb-2">🏪</div> No stalls yet, check back soon!
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div x-show="stallModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="stallModal = false" class="bg-white rounded-[2rem] max-w-md w-full p-8 shadow-2xl border border-gray-100 space-y-4">
            <div>
                <h3 class="text-xl font-black text-[#0B318F]">Rent Stall Space #<span x-text="activeStallNum"></span></h3>
                <p class="text-xs text-gray-500">Provide your account details to register a pending lease booking request.</p>
            </div>
            <form method="POST" action="{{ route('student.book_stall.store') }}" class="space-y-4 text-sm">
                @csrf
                <input type="hidden" name="stall_id" :value="activeStallId">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" required value="{{ Auth::user()->name }}" class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" required class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Company / Association (Optional)</label>
                    <input type="text" name="company" class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Booking Date (Wednesdays Only)</label>
                    <input type="date" name="booking_date" required 
                           min="{{ date('Y-m-d') }}"
                           @change="
                               let dateParts = $el.value.split('-');
                               if (dateParts.length === 3) {
                                   // Explicit initialization ensures native local system day constraints mapping
                                   let dayChosen = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]).getDay();
                                   if (dayChosen !== 3) {
                                       dateError = '⚠️ Stalls can only be reserved for Wednesdays!';
                                       $el.value = ''; // Instantly wipes input box
                                   } else {
                                       dateError = ''; // Clear error state if input matches Wednesday
                                   }
                               }
                           "
                           class="w-full bg-gray-50 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:border-purple-600">
                    
                    <span x-show="dateError" x-text="dateError" class="text-red-600 text-xs font-bold mt-1.5 block px-1"></span>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="stallModal = false" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-full text-xs uppercase tracking-wider transition">Cancel</button>
                    <button type="submit" class="w-1/2 bg-[#9E007E] hover:bg-[#800066] text-white font-bold py-2.5 rounded-full text-xs uppercase tracking-wider transition shadow">Confirm Rent</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>