<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    <!-- Top Navigation Bar Include -->
    @include('layouts.user-navbar')

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar Include -->
        @include('layouts.user-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-y-auto">
            
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-black leading-tight">
                    {{ __('My Account Profile') }}
                </h2>
                <p class="text-xs text-black mt-1">This is your profile information..</p>
            </div>

            <!-- Profile Details Box (Everything contained inside, white background forced) -->
            <div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8 space-y-6">
                
                <!-- Profile Header with PFP at the top -->
                <div class="flex flex-col items-center text-center space-y-3 pb-4 border-b border-gray-100">
                    <div class="w-20 h-20 bg-purple-900 border border-gray-200 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-black">{{ Auth::user()->name }}</h3>
                        <span class="inline-block mt-1 px-3 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-full uppercase">
                            Student or UPSI Personnel
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-black uppercase tracking-wider block">Email Address</span>
                        <div class="font-medium text-black py-1">
                            {{ Auth::user()->email }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-black uppercase tracking-wider block">Phone Contact</span>
                        <div class="font-medium text-black py-1 font-mono">
                            {{ Auth::user()->phone ?? 'No Phone Line Provided' }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-black uppercase tracking-wider block">Account Created Date</span>
                        <div class="font-medium text-black py-1">
                            {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('F d, Y') : 'System Era Record' }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-black uppercase tracking-wider block">User ID</span>
                        <div class="font-medium text-black py-1 font-mono text-xs">
                            UID: #{{ Auth::user()->id }}
                        </div>
                    </div>

                </div>

                <!-- Footer Info Section -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex gap-3 items-start">
                    <span class="text-lg">ℹ️</span>
                    <div class="text-xs text-black leading-relaxed">
                        <strong>Need to change your credentials or connection profile?</strong> Contact UKLK staff for assistance.
                    </div>
                </div>
                
            </div>

        </main>
    </div>

</body>
</html>