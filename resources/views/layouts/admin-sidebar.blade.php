<aside class="w-64 bg-purple-900 text-white flex flex-col shadow-xl border-r border-purple-800">
    <div class="p-6 border-b border-purple-800 flex items-center gap-3">
        <span class="text-xl font-black tracking-wider text-amber-400">ADMIN</span>
        <span class="text-sm font-medium opacity-75">Portal</span>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition font-medium {{ request()->routeIs('dashboard') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">dashboard</span> Dashboard
        </a>

        <div class="pt-4 pb-1 px-4 text-xs font-semibold text-purple-300 uppercase tracking-wider">Reservations</div>
        
        <a href="{{ route('admin_user.stall_bookings') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.stall_bookings') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">storefront</span> Stall Bookings
        </a>
        
        <a href="{{ route('admin_user.item_bookings') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.item_bookings') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">inventory_2</span> Item Bookings
        </a>
        
        <a href="{{ route('admin_user.venue_bookings') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.venue_bookings') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">weekend</span> Lounge Bookings
        </a>
        
        <a href="{{ route('admin_user.cabin_bookings') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.cabin_bookings') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">storefront</span> Cabin Bookings
        </a>


        <div class="pt-4 pb-1 px-4 text-xs font-semibold text-purple-300 uppercase tracking-wider">Inventories</div>
        
        <a href="{{ route('admin_user.stall_parking') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.stall_parking*') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">table_chart</span> List of Stalls
        </a>
        
        <a href="{{ route('admin_user.item') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.item') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">category</span> List of Items
        </a>

        <a href="{{ route('admin_user.cabin') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.cabin') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">storefront</span> List of Cabins
        </a>

        <a href="{{ route('admin_user.lounge') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin_user.lounge') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">weekend</span> List of Lounges
        </a>

        <div class="pt-4 pb-1 px-4 text-xs font-semibold text-purple-300 uppercase tracking-wider">User Registers</div>

        <a href="{{ route('admin.users.index', ['tab' => 'app']) }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin.users.index') && request()->query('tab') === 'app' ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">phone_android</span> App Users
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'web']) }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin.users.index') && request()->query('tab', 'web') !== 'app' ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">language</span> Web Users
        </a>

        <div class="pt-6 border-t border-purple-800"></div>

        <a href="{{ route('admin.analytics.index') }}" 
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition font-medium {{ request()->routeIs('admin.analytics.index') ? 'bg-purple-800 text-white border-l-4 border-amber-400' : 'text-purple-100 hover:bg-purple-800 hover:text-white' }}">
            <span class="material-icons text-lg">analytics</span> Analytics & Reports
        </a>
    </nav>
</aside>