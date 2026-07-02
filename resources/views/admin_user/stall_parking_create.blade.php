<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Register Parking Slot</h2>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm max-w-2xl">
                <form action="{{ route('admin_user.stall_parking_store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stall Number / Row</label>
                            <input type="text" name="stall_number" value="{{ old('stall_number') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('stall_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location Zone</label>
                            <input type="text" name="location" value="{{ old('location') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Capacity Slots</label>
                            <input type="number" name="total_slots" value="{{ old('total_slots', 1) }}" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('total_slots') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Roof Configuration</label>
                            <select name="is_roofed" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="0" {{ old('is_roofed') == '0' ? 'selected' : '' }}>Open Sky</option>
                                <option value="1" {{ old('is_roofed') == '1' ? 'selected' : '' }}>Roofed Structural Roof</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Operational Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student Rate (RM/day)</label>
                            <input type="number" step="0.01" name="student_rate" value="{{ old('student_rate') }}" required min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('student_rate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vendor Rate (RM/day)</label>
                            <input type="number" step="0.01" name="vendor_rate" value="{{ old('vendor_rate') }}" required min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @error('vendor_rate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-4 mt-6">
                        <a href="{{ route('admin_user.stall_parking') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        <button type="submit" class="bg-purple-900 hover:bg-purple-800 text-white font-medium text-sm px-5 py-2 rounded-xl transition cursor-pointer shadow-sm">
                            Save Record
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>