<aside x-show="sidebarOpen" 
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="w-64 bg-[#1B49A6] text-white flex flex-col justify-between shadow-xl fixed lg:relative z-10 h-[calc(100vh-64px)] overflow-y-auto">
    
    <nav class="p-4 space-y-1 flex-1">
        <a href="{{ route('student.my_bookings') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.my_bookings') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            My Bookings
        </a>
        <a href="{{ route('student.my_borrows') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.my_borrows') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            My borrows
        </a>
        <a href="{{ route('student.student_lounge') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.student_lounge') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            Student Lounge
        </a>
        <a href="{{ route('student.stalls') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.stalls') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            Stalls
        </a>
        <a href="{{ route('student.equipments') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.equipments') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            Equipments
        </a>
        <a href="{{ route('student.cibo_cabin') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm font-medium {{ request()->routeIs('student.cibo_cabin') ? 'bg-white/20 font-bold' : 'hover:bg-white/10' }}">
            Cibo Cabin
        </a>
    </nav>

    <div class="p-5 border-t border-white/10 flex items-center justify-between bg-[#163D8C]">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-semibold hover:text-red-300 transition cursor-pointer bg-transparent border-none p-0">
                Log Out
            </button>
        </form>

        <button class="text-white/80 hover:text-white transition cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
    </div>
</aside>