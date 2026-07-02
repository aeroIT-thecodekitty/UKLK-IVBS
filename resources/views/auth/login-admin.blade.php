<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login | UKLK</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">

    <!-- Main Screen Container referencing Home.png verbatim for the background -->
<div class="min-h-screen bg-cover bg-center bg-no-repeat relative flex flex-col" 
         style="background-image: url('{{ asset('images/admin_bg.png') }}');">
        
        <!-- Subtle dark tint overlay to improve form visual contrast -->
        <div class="absolute inset-0 bg-slate-950/20 mix-blend-multiply pointer-events-none"></div>

        <!-- Render Separated Header Navbar Component -->
        @include('layouts.home-navbar')

        <!-- Authentication Portal Box Panel Wrap -->
        <main class="relative z-10 flex-1 flex items-center justify-start px-12 md:px-20 lg:px-24 py-8">
            
            <!-- Highly Rounded Login Card Box Context -->
            <div class="bg-slate-50/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/40 p-10 w-full max-w-md flex flex-col justify-between min-h-[520px]">
                
                <!-- Inner Processing Post Form targeting your explicit admin endpoint -->
                <form method="POST" action="{{ url('login/admin') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Form Header Section Title -->
                    <div class="text-center md:text-left mb-6">
                        <h2 class="text-2xl font-black text-[#0B318F] tracking-wide">Admin Login</h2>
                        <p class="text-xs text-gray-500 mt-1">To access this, you must be an authorized administrator.</p>
                    </div>

                    <!-- Global Error Handling Block Alert -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-2xl text-xs">
                            <ul class="list-disc list-inside space-y-0.5 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Input 1: Administrator Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-gray-700 px-2">Administrator Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full bg-white border border-gray-300 rounded-full py-3 px-6 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                    </div>

                    <!-- Input 2: Security Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-gray-700 px-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full bg-white border border-gray-300 rounded-full py-3 px-6 text-gray-800 shadow-inner focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm">
                    </div>

                    <!-- CTA Processing: Verification Authentication Trigger -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-[#9E007E] hover:bg-[#800066] active:bg-[#660052] text-white font-bold py-3.5 px-6 rounded-full shadow-lg transition duration-200 transform active:scale-[0.99] cursor-pointer text-sm text-center tracking-wider uppercase">
                            Log In as Admin
                        </button>
                    </div>
                </form>

                <!-- Footer Return Redirection Panel -->
                <div class="flex flex-col items-center pt-4 border-t border-gray-200/50 mt-6">
                    <a href="{{ url('/') }}" class="text-xs font-bold text-gray-600 hover:text-[#0B318F] hover:underline transition duration-200 flex items-center gap-1.5 no-underline">
                        ← Return to Portal Selection
                    </a>
                </div>

            </div>
        </main>
    </div>

</body>
</html>