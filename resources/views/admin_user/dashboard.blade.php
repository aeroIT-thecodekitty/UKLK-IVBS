<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Control System Dashboard') }}
                </h2>
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-purple-900 dark:text-purple-300">Live Connection</span>
            </div>

            <div class="bg-gradient-to-r from-purple-800 to-purple-600 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm opacity-90 mt-2">
                        <span class="flex items-center gap-1"><span class="font-semibold text-amber-300">Account ID:</span> {{ Auth::user()->id }}</span>
                        <span class="flex items-center gap-1"><span class="font-semibold text-amber-300">System Role:</span> {{ Auth::user()->role }}</span>
                    </div>
                </div>
                <div class="absolute right-0 bottom-0 translate-x-10 translate-y-10 w-40 h-40 bg-purple-500 rounded-full opacity-20 pointer-events-none"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    {{ __("Administrative session active and synchronized with Supabase cloud infrastructure.") }}
                </div>
            </div>
        </main>
    </div>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</x-app-layout>