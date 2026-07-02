<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UKLK Item and Venue Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">

    <div class="min-h-screen bg-cover bg-center bg-no-repeat relative flex flex-col" 
         style="background-image: url('{{ asset('images/home_bg.jpg') }}');">
        
        <div class="absolute inset-0 bg-slate-900/10 mix-blend-multiply pointer-events-none"></div>

        @include('layouts.home-navbar')

        <main class="relative z-10 flex-1 flex items-center justify-start px-12 md:px-20 lg:px-24 py-8">
            
            <div class="bg-slate-50/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/40 p-8 w-full max-w-xl flex flex-col justify-between">
                
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    <div class="text-center md:text-left mb-4">
                        <h2 class="text-3xl font-black text-[#0B318F] tracking-wide">Register Account</h2>
                        <p class="text-xs text-gray-500 mt-1">Set up your credentials to access the booking ecosystem.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-2xl text-xs">
                            <ul class="list-disc list-inside space-y-0.5 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div class="space-y-1">
                            <label for="name" class="block text-xs font-bold text-gray-700 px-2">Full Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                   class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-bold text-gray-700 px-2">E-mail address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                   class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="phone" class="block text-xs font-bold text-gray-700 px-2">Phone Number</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                   class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="role" class="block text-xs font-bold text-gray-700 px-2">Register As</label>
                            <div class="relative">
                                <select id="role" name="role" required 
                                        class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm appearance-none cursor-pointer">
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student/UPSI Personnel</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-500 text-xs">
                                    ▼
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-bold text-gray-700 px-2">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 px-2">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full bg-white border border-gray-300 rounded-full py-2.5 px-5 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                        </div>

                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-[#9E007E] hover:bg-[#800066] active:bg-[#660052] text-white font-bold py-3.5 px-6 rounded-full shadow-lg transition duration-200 transform active:scale-[0.99] cursor-pointer text-sm text-center tracking-wider uppercase">
                            Register
                        </button>
                    </div>
                </form>

                <div class="flex flex-col items-center space-y-3 pt-4 border-t border-gray-200/50 mt-5">
                    <p class="text-sm font-bold text-gray-800">Have an account?</p>
                    
                    <a href="{{ route('login') }}" 
                       class="w-full bg-[#0B318F] hover:bg-[#072266] active:bg-[#041440] text-white font-bold py-3.5 px-6 rounded-full shadow-lg transition duration-200 transform active:scale-[0.99] text-sm text-center tracking-wider no-underline block">
                        Log In Instead
                    </a>
                </div>

            </div>
        </main>
    </div>

</body>
</html>