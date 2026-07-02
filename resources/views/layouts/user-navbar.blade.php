<header class="w-full bg-[#0B318F] text-white flex items-center justify-between px-6 py-3 shadow-md z-20">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-gray-200 transition focus:outline-none cursor-pointer">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/uklk_logo.png') }}" alt="UKLK Logo" class="h-10 w-auto object-contain brightness-0 invert">
            <h1 class="text-lg font-bold tracking-wide hidden sm:block">
                UKLK Item and Venue Booking System
            </h1>
        </div>
    </div>

    <nav class="flex items-center gap-6 text-sm font-semibold tracking-wide">
        <a href="{{ route('student.home') }}" class="hover:text-amber-400 transition no-underline">Home</a>
        <a href="{{ route('student.profile') }}" class="hover:text-amber-400 transition no-underline">My Profile</a>
    </nav>
</header>