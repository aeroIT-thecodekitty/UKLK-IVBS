<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKLK Item and Venue Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">

    <div class="min-h-screen bg-cover bg-center bg-no-repeat relative flex flex-col" 
         style="background-image: url('{{ asset('images/home_bg.jpg') }}');">
        
        <div class="absolute inset-0 bg-slate-900/10 mix-blend-multiply pointer-events-none"></div>

        @include('layouts.home-navbar')

        <main class="relative z-10 flex-1 flex items-center justify-start px-12 md:px-20 lg:px-24">
            
            <div class="bg-slate-50/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/40 p-10 w-full max-w-md flex flex-col justify-between min-h-[500px]">
                
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="text-center md:text-left mb-6">
                        <h2 class="text-3xl font-black text-[#0B318F] tracking-wide">Login</h2>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700 px-2">E-mail address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full bg-white border border-gray-300 rounded-full py-3 px-6 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-md">
                        @error('email')
                            <p class="text-red-500 text-xs px-3 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700 px-2">Password</label>
                        <input type="password" name="password" required 
                               class="w-full bg-white border border-gray-300 rounded-full py-3 px-6 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-md">
                        @error('password')
                            <p class="text-red-500 text-xs px-3 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end text-sm px-2">
                        <a href="{{ route('password.request') }}" class="font-bold text-gray-800 hover:text-purple-800 hover:underline transition">
                            Forgot Password?
                        </a>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-[#9E007E] hover:bg-[#800066] active:bg-[#660052] text-white font-bold py-3.5 px-6 rounded-full shadow-lg transition duration-200 transform active:scale-[0.99] cursor-pointer text-md text-center tracking-wider">
                            Log In
                        </button>
                    </div>
                </form>

                <div class="flex flex-col items-center space-y-4 pt-6 border-t border-gray-200/50 mt-6">
                    <p class="text-sm font-bold text-gray-800">Don’t have an account?</p>
                    
                    <a href="{{ route('register') }}" 
                       class="w-full bg-[#0B318F] hover:bg-[#072266] active:bg-[#041440] text-white font-bold py-3.5 px-6 rounded-full shadow-lg transition duration-200 transform active:scale-[0.99] text-md text-center tracking-wider no-underline block">
                        Register
                    </a>
                </div>

            </div>
        </main>
    </div>

</body>
</html>