<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900" x-data="{ showCreateModal: false }">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Cibo Cabins Management') }}
                </h2>
                <div class="flex items-center gap-4">
                    @if(session('success'))
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-4 py-1.5 rounded-lg shadow-sm">
                            {{ session('success') }}
                        </span>
                    @endif
                    <button @click="showCreateModal = true" class="bg-purple-900 hover:bg-purple-800 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition cursor-pointer">
                        + Add New Cabin
                    </button>
                </div>
            </div>

            <!-- READ: Data Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-purple-950 text-purple-200 text-sm">
                            <th class="p-4">Cabin No.</th>
                            <th class="p-4">Location</th>
                            <th class="p-4">Specs & Slots</th>
                            <th class="p-4">Rental Rate</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($cabins as $cabin)
                            <!-- Row Scope for Edit Modal -->
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300 transition" x-data="{ showEditModal: false }">
                                <td class="p-4 font-bold text-purple-700 dark:text-purple-400">#{{ $cabin->cabin_number }}</td>
                                <td class="p-4 text-sm">{{ $cabin->location }}</td>
                                <td class="p-4 text-sm">
                                    <div class="font-semibold">{{ $cabin->total_slots }} Slots Total</div>
                                    <div class="text-xs text-gray-500">{{ $cabin->is_roofed ? 'Roofed Area' : 'Open Air' }}</div>
                                </td>
                                <td class="p-4 text-sm font-medium text-green-600 dark:text-green-400">
                                    RM {{ number_format($cabin->rate, 2) }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold {{ $cabin->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} border rounded-full">
                                        {{ ucfirst($cabin->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <!-- UPDATE: Trigger Modal -->
                                    <button @click="showEditModal = true" class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-3 py-1.5 rounded-md text-xs font-medium transition cursor-pointer">Edit</button>
                                    
                                    <!-- DELETE: Form -->
                                    <form action="{{ route('admin_user.cabin.destroy', $cabin->id) }}" method="POST" onsubmit="return confirm('Delete this cabin permanently?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-medium transition cursor-pointer">Delete</button>
                                    </form>
                                </td>

                                <!-- UPDATE: Modal -->
                                <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                                    <div @click.away="showEditModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 text-left">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Edit Cabin #{{ $cabin->cabin_number }}</h3>
                                        <form action="{{ route('admin_user.cabin.update', $cabin->id) }}" method="POST" class="space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cabin Number</label>
                                                <input type="text" name="cabin_number" value="{{ $cabin->cabin_number }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                                <input type="text" name="location" value="{{ $cabin->location }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Slots</label>
                                                    <input type="number" name="total_slots" value="{{ $cabin->total_slots }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate (RM)</label>
                                                    <input type="number" step="0.01" name="rate" value="{{ $cabin->rate }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roofing</label>
                                                    <select name="is_roofed" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="1" {{ $cabin->is_roofed ? 'selected' : '' }}>Roofed</option>
                                                        <option value="0" {{ !$cabin->is_roofed ? 'selected' : '' }}>Open Air</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                                    <select name="status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="available" {{ $cabin->status === 'available' ? 'selected' : '' }}>Available</option>
                                                        <option value="maintenance" {{ $cabin->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                                <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ $cabin->description }}</textarea>
                                            </div>
                                            <div class="flex justify-end gap-3 mt-6">
                                                <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">Cancel</button>
                                                <button type="submit" class="bg-purple-900 hover:bg-purple-800 text-white px-4 py-2 rounded-lg font-medium transition cursor-pointer">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-8 text-center text-gray-400 text-sm italic">No cabins exist in the database.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- CREATE: Modal -->
            <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div @click.away="showCreateModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 text-left">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add New Cabin</h3>
                    <form action="{{ route('admin_user.cabin.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cabin Number</label>
                            <input type="text" name="cabin_number" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" name="location" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Slots</label>
                                <input type="number" name="total_slots" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate (RM)</label>
                                <input type="number" step="0.01" name="rate" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roofing</label>
                                <select name="is_roofed" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="1">Roofed</option>
                                    <option value="0">Open Air</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="available">Available</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">Cancel</button>
                            <button type="submit" class="bg-purple-900 hover:bg-purple-800 text-white px-4 py-2 rounded-lg font-medium transition cursor-pointer">Create Cabin</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>