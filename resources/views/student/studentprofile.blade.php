<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Account Profile') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Manage your student portal identity details and account status verification nodes.</p>
            </div>

            <div class="max-w-3xl bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <div class="h-32 bg-gradient-to-r流 from-[#1B49A6] to-[#163D8C] p-6 flex items-end">
                    <div class="flex items-center gap-4 translate-y-10">
                        <div class="w-20 h-20 bg-purple-900 border-4 border-white dark:border-gray-800 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-md">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                </div>

                <div class="pt-14 p-8 space-y-6">
                    
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                        <span class="inline-block mt-1 px-3 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-full uppercase">
                            Student or UPSI Personnel
                        </span>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Email Address Connection</span>
                            <div class="font-medium text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/60 dark:border-gray-600">
                                {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Phone Contact Line</span>
                            <div class="font-medium text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/60 dark:border-gray-600 font-mono">
                                {{ Auth::user()->phone ?? 'No Phone Line Provided' }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Account Created Date</span>
                            <div class="font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/60 dark:border-gray-600">
                                {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('F d, Y') : 'System Era Record' }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">System Node Reference</span>
                            <div class="font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/60 dark:border-gray-600 font-mono text-xs">
                                UID: #{{ Auth::user()->id }}
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 bg-purple-50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900 rounded-xl p-4 flex gap-3 items-start">
                        <span class="text-lg">ℹ️</span>
                        <div class="text-xs text-purple-900 dark:text-purple-400 leading-relaxed">
                            <strong>Need to change your credentials or connection profile?</strong> Profile credential modifications can be addressed directly via the global system dashboard hub execution paths or by notifying your administration manager network nodes.
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>
</x-app-layout>