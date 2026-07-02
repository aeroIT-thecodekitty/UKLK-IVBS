<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Parking Inventory Registry') }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Live data controls syncing instantly with your Flutter mobile grid selection array.</p>
                </div>
                <a href="{{ route('admin_user.stall_parking_create') }}" class="bg-purple-900 hover:bg-purple-800 text-white font-medium text-sm px-4 py-2 rounded-xl transition cursor-pointer shadow-sm no-underline inline-block">
                    + Register New Slot
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Active Slots</div>
                    <div class="text-3xl font-black text-purple-900 dark:text-purple-400 mt-1">{{ $stalls->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Roofed Overhead Units</div>
                    <div class="text-3xl font-black text-blue-600 mt-1">{{ $stalls->where('is_roofed', true)->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Maintenance Suspensions</div>
                    <div class="text-3xl font-black text-red-500 mt-1">{{ $stalls->where('status', 'maintenance')->count() }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($stalls as $stall)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border {{ $stall->status === 'maintenance' ? 'border-red-200' : 'border-gray-100' }} dark:border-gray-700 shadow-sm overflow-hidden flex flex-col justify-between">
                        
                        <div class="p-5 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Stall Row: {{ $stall->stall_number }}</h4>
                                    <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                        <span class="material-icons text-xs">location_on</span> {{ $stall->location }}
                                    </p>
                                </div>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-md {{ $stall->is_roofed ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $stall->is_roofed ? 'Roofed Structural Roof' : 'Open Sky' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg text-xs">
                                <div>
                                    <span class="text-gray-400 font-medium block">Student Rate:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">RM {{ number_format($stall->student_rate, 2) }}/day</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 font-medium block">Vendor Rate:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">RM {{ number_format($stall->vendor_rate, 2) }}/day</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $stall->status === 'available' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                <span class="text-xs font-bold uppercase tracking-wider {{ $stall->status === 'available' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $stall->status }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin_user.stall_parking_edit', $stall->id) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                                    Edit
                                </a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('admin_user.stall_parking_destroy', $stall->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this parking unit?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 transition cursor-pointer bg-transparent border-0 p-0">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white text-center p-12 rounded-xl border border-gray-200 text-gray-400 italic text-sm">
                        No active records populated inside your `stall_parking` dataset yet.
                    </div>
                @endforelse
            </div>

        </main>
    </div>
</x-app-layout>