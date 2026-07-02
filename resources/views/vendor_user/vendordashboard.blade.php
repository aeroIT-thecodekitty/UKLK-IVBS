<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    @include('layouts.vendor-navbar')

    <div class="flex flex-1 relative">
        @include('layouts.vendor-sidebar')

        <main class="flex-1 p-8 space-y-6 overflow-x-hidden">
            
            <div class="w-full bg-[#EBF1FA] border border-slate-300 rounded-[1.25rem] p-5 shadow-sm max-w-xl">
                <h3 class="text-lg font-medium text-slate-800">
                    Welcome, {{ Auth::user()->name }}!
                </h3>
                <p class="text-sm font-semibold text-slate-700 mt-1">
                    ID: {{ sprintf('%04d', Auth::user()->id) }}
                </p>
                <p class="text-sm font-semibold text-slate-700 mt-1">
                    Role: {{ Auth::user()->role }}
                </p>
            </div>

            <div class="space-y-1">
                <h2 class="text-2xl font-bold text-[#0B318F]">Looking for something?</h2>
                <h4 class="text-lg font-extrabold text-[#0B318F] pt-2">Featured</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl">
                
                <a href="{{ route('student.stalls') }}" class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col justify-between border border-slate-100 group transition hover:shadow-lg no-underline block">
                    <div class="h-44 w-full overflow-hidden bg-slate-100">
                        <img src="{{ asset('images/featured_stalls.jpg') }}" alt="Stalls" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="bg-[#0B318F] text-white p-4 flex justify-between items-center">
                        <span class="font-bold text-md">Stalls</span>
                        <span class="text-xs opacity-90 font-medium">From RM 30</span>
                    </div>
                </a>


            </div>
        </main>
    </div>

</body>
</html>