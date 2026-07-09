<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900" 
         x-data="{ 
            currentTab: '{{ request()->get('tab', 'web') }}', 
            webModal: false, 
            appModal: false,
            isEdit: false,
            actionUrl: '',
            form: { id: '', name: '', email: '', phone: '', role: '' }
         }">
        
        @include('layouts.admin-sidebar')

        <main class="flex-1 p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('System User Accounts Registry') }}
                </h2>
                @if(session('success'))
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-4 py-1.5 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </span>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex gap-2 w-full sm:max-w-md">
                    <button @click="currentTab = 'web'; window.history.replaceState(null, null, '?tab=web')" 
                            :class="currentTab === 'web' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" 
                            class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition cursor-pointer text-center">
                        💻 Web Users ({{ $webUsers->count() }})
                    </button>
                    <button @click="currentTab = 'app'; window.history.replaceState(null, null, '?tab=app')" 
                            :class="currentTab === 'app' ? 'bg-purple-900 text-white shadow' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" 
                            class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition cursor-pointer text-center">
                        📱 App Users ({{ $appUsers->count() }})
                    </button>
                </div>

                <button @click="
                    isEdit = false; 
                    form = { id: '', name: '', email: '', phone: '', role: 'student' };
                    if(currentTab === 'web') { actionUrl = '{{ route('admin.users.web.store') }}'; webModal = true; }
                    else { actionUrl = '{{ route('admin.users.app.store') }}'; appModal = true; }
                " class="w-full sm:w-auto bg-purple-900 hover:bg-purple-800 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow-sm cursor-pointer text-center">
                    + Register New User
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    
                    <tbody x-show="currentTab === 'web'" class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr class="bg-purple-950 text-purple-200 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">User Details</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Phone Number</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                        @forelse($webUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 text-gray-700 dark:text-gray-300 transition text-sm">
                                <td class="p-4 font-bold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                    <div class="text-[10px] text-gray-400 font-mono font-normal">UID: #{{ $user->id }}</div>
                                </td>
                                <td class="p-4 font-medium">{{ $user->email }}</td>
                                <td class="p-4 text-xs font-mono">{{ $user->phone ?? '-' }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-0.5 text-xs font-bold border rounded-full uppercase
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border-red-200' : ($user->role === 'student' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                        {{ $user->role === 'student' ? 'Student or UPSI Personnel' : $user->role }}
                                    </span>
                                </td>
                                <td class="p-4 text-right flex justify-end items-center gap-2">
                                    <button @click="
                                        isEdit = true;
                                        actionUrl = '{{ url('admin/users-manager/web') }}/' + '{{ $user->id }}' + '/update';
                                        form = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', phone: '{{ $user->phone }}', role: '{{ $user->role }}' };
                                        webModal = true;
                                    " class="bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 text-xs px-2.5 py-1 rounded transition font-bold cursor-pointer">Edit</button>
                                    
                                    <form action="{{ route('admin.users.web.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this web user profile?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 border border-red-100 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950/30 text-xs px-2.5 py-1 rounded transition font-medium cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-8 text-center text-gray-400 italic">No web records tracked inside database.</td></tr>
                        @endforelse
                    </tbody>

                    <tbody x-show="currentTab === 'app'" class="divide-y divide-gray-100 dark:divide-gray-700" x-cloak style="display: none;">
                        <tr class="bg-purple-950 text-purple-200 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4">Name</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Phone Number</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                        @forelse($appUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 text-gray-700 dark:text-gray-300 transition text-sm">
                                <td class="p-4 font-bold text-purple-900 dark:text-purple-400">
                                    {{ $user->name ?? 'Mobile app Profile' }}
                                    <div class="text-[10px] text-gray-400 font-mono font-normal" title="{{ $user->id }}">
                                        UUID: {{ substr($user->id, 0, 8) }}...
                                    </div>
                                </td>
                                <td class="p-4 font-medium">{{ $user->email }}</td>
                                <td class="p-4 text-xs font-mono">{{ $user->phone ?? '-' }}</td>
                                <td class="p-4 text-right flex justify-end items-center gap-2">
                                    <button @click="
                                        isEdit = true;
                                        actionUrl = '{{ url('admin/users-manager/app') }}/' + '{{ $user->id }}' + '/update';
                                        form = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', phone: '{{ $user->phone }}', role: '' };
                                        appModal = true;
                                    " class="bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 text-xs px-2.5 py-1 rounded transition font-bold cursor-pointer">Edit</button>
                                    
                                    <form action="{{ route('admin.users.app.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this account?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 border border-red-100 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950/30 text-xs px-2.5 py-1 rounded transition font-medium cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-8 text-center text-gray-400 italic">No mobile app authentication rows found inside auth.users schema.</td></tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </main>

        <div x-show="webModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
            <div @click.away="webModal = false" class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-8 shadow-2xl space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-purple-950 dark:text-purple-400" x-text="isEdit ? 'Edit Web Account Profile' : 'Register New Web User Node'"></h3>
                <form method="POST" :action="actionUrl" class="space-y-3 text-xs text-gray-700 dark:text-gray-300">
                    @csrf
                    <div>
                        <label class="block font-bold mb-1">Full Name</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Email Address</label>
                        <input type="email" name="email" x-model="form.email" required class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Phone Number</label>
                        <input type="text" name="phone" x-model="form.phone" class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Roles</label>
                        <select name="role" x-model="form.role" required class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                            <option value="admin">Admin Executive</option>
                            <option value="student">Student or UPSI Personnel</option>
                            <option value="vendors">Vendor Merchant Operator</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Password <span x-show="isEdit" class="text-gray-400 font-normal">(Leave blank to keep unchanged)</span></label>
                        <input type="password" name="password" :required="!isEdit" class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="webModal = false" class="w-1/2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white font-bold py-2 rounded-lg transition cursor-pointer">Cancel</button>
                        <button type="submit" class="w-1/2 bg-purple-900 text-white font-bold py-2 rounded-lg transition shadow-sm cursor-pointer" x-text="isEdit ? 'Save Changes' : 'Confirm Save'"></button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="appModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak style="display: none;">
            <div @click.away="appModal = false" class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-8 shadow-2xl space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-purple-950 dark:text-purple-400" x-text="isEdit ? 'Modify Supabase Profile' : 'Register New Supabase Mobile Profile'"></h3>
                <form method="POST" :action="actionUrl" class="space-y-3 text-xs text-gray-700 dark:text-gray-300">
                    @csrf
                    <div>
                        <label class="block font-bold mb-1">Full Username</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Email Account Connection</label>
                        <input type="email" name="email" x-model="form.email" required class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Phone Line</label>
                        <input type="text" name="phone" x-model="form.phone" class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Account Password <span x-show="isEdit" class="text-gray-400 font-normal">(Leave blank to keep unchanged)</span></label>
                        <input type="password" name="password" :required="!isEdit" class="w-full bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg p-2 focus:outline-none focus:border-purple-600">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="appModal = false" class="w-1/2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white font-bold py-2 rounded-lg transition cursor-pointer">Cancel</button>
                        <button type="submit" class="w-1/2 bg-purple-900 text-white font-bold py-2 rounded-lg transition shadow-sm cursor-pointer" x-text="isEdit ? 'Save Details' : 'Deploy Account'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>