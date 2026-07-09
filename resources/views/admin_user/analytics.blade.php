<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Operational Insights & Analytics') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Real-time revenue audits, asset utilization tracking, and registration statistics.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div class="text-gray-400 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Gross Income Revenue</div>
                    <div class="text-2xl font-black text-purple-900 dark:text-purple-400 mt-2">RM {{ number_format($totalRevenue, 2) }}</div>
                    <div class="text-[11px] text-gray-400 mt-2">
                        Stalls: <span class="font-bold text-gray-600 dark:text-gray-300">RM{{ number_format($stallRevenue, 0) }}</span> • 
                        Cabins: <span class="font-bold text-gray-600 dark:text-gray-300">RM{{ number_format($cabinRevenue, 0) }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div class="text-gray-400 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Awaiting Approvals</div>
                    <div class="text-2xl font-black text-orange-600 mt-2">{{ $totalPendingRequests }} Requests pending</div>
                    <div class="text-[11px] text-orange-500 font-medium mt-2">Go verify them in the bookings page if you have any.</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div class="text-gray-400 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Active Borrowed Equipment</div>
                    <div class="text-2xl font-black text-blue-600 mt-2">{{ $activeLoansCount }} Active Borrowings</div>
                    <div class="text-[11px] text-gray-400 mt-2">Outstanding equipment currently being borrowed.</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div class="text-gray-400 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Users</div>
                    <div class="text-2xl font-black text-gray-800 dark:text-white mt-2">{{ $totalWebUsers + $totalAppUsers }} Accounts</div>
                    <div class="text-[11px] text-gray-400 mt-2">
                        💻 Web Platform: <span class="font-bold text-gray-600 dark:text-gray-300">{{ $totalWebUsers }}</span> • 
                        📱 App Auth: <span class="font-bold text-gray-600 dark:text-gray-300">{{ $totalAppUsers }}</span>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-red-50 dark:bg-red-950/20 px-5 py-4 border-b border-red-100 dark:border-red-900 flex items-center justify-between">
                        <h3 class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-wide flex items-center gap-1.5">
                            🚨 Overdue Equipment Return
                        </h3>
                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $overdueEquipment->count() }} Delinquent</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs text-gray-700 dark:text-gray-300">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-400 font-bold uppercase border-b border-gray-100 dark:border-gray-700">
                                    <th class="p-3">Borrower Student</th>
                                    <th class="p-3">Item Details</th>
                                    <th class="p-3">Was Due At</th>
                                    <th class="p-3 text-right">Contact Line</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($overdueEquipment as $log)
                                    <tr class="hover:bg-red-50/30 transition">
                                        <td class="p-3 font-bold text-gray-900 dark:text-white">{{ $log->student_name }}</td>
                                        <td class="p-3 font-medium text-purple-700 dark:text-purple-400">{{ $log->item_name }} <span class="text-gray-400">({{ $log->quantity_requested }}x)</span></td>
                                        <td class="p-3 font-mono font-bold text-red-600">{{ \Carbon\Carbon::parse($log->end_time)->format('M d, h:i A') }}</td>
                                        <td class="p-3 text-right font-mono text-gray-600 dark:text-gray-400 font-bold">{{ $log->student_phone ?? 'No Phone Logged' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-400 italic font-medium bg-gray-50/30">
                                            Awesome! All equipment has been safely returned to the office on time.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-black text-purple-950 dark:text-purple-400 uppercase tracking-wide">
                                📋 Recent Item Drop-off Evaluations
                            </h3>
                        </div>
                        <div class="p-5 space-y-4">
                            @forelse($assetIncidents as $incident)
                                <div class="border-l-4 border-purple-800 pl-3 space-y-1">
                                    <div class="text-xs font-bold text-gray-900 dark:text-white">
                                        {{ $incident->student_name }} <span class="text-gray-400 font-normal">returned</span> {{ $incident->item_name }}
                                    </div>
                                    <div class="text-[11px] text-amber-800 dark:text-amber-500 font-medium italic bg-amber-50 dark:bg-amber-950/20 px-2 py-1 rounded-md border border-amber-100 dark:border-amber-900">
                                        "{{ $incident->return_condition }}"
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono">
                                        {{ \Carbon\Carbon::parse($incident->returned_at)->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 italic text-center py-12">
                                    No recent equipment return evaluations have been logged yet. All items are in good condition.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">End of System Logs Matrix</span>
                    </div>
                </div>

            </div>

        </main>
    </div>
</x-app-layout>