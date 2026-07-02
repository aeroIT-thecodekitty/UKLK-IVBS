<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to UKLK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <div class="min-h-screen bg-cover bg-center bg-no-repeat relative flex flex-col flex-1" 
         style="background-image: url('{{ asset('images/home_bg.jpg') }}');">
        
        <div class="absolute inset-0 bg-slate-900/10 mix-blend-multiply pointer-events-none"></div>

        @include('layouts.home-navbar')

        <main class="relative z-10 flex-1 flex items-center justify-center px-6 md:px-12 lg:px-20 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-5xl items-stretch">
                
                <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/50 p-10 flex flex-col justify-between text-center min-h-[420px] transition duration-300 hover:shadow-white/10">
                    <div class="space-y-4">
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0B318F] leading-tight tracking-tight px-4">
                            Log in as UPSI Personnel/Student or Vendor
                        </h2>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed px-2">
                            Register or log in and manage your bookings and item borrowings, and access your account.
                        </p>
                    </div>
                    
                    <div class="mt-8 space-y-4">
                        <a href="{{ route('login') }}" 
                           class="w-full bg-[#0B318F] hover:bg-[#072266] active:bg-[#041440] text-white font-bold py-3.5 px-6 rounded-full shadow-md hover:shadow-xl transition duration-200 block text-md tracking-wide transform active:scale-[0.99]">
                            Log in as UPSIAN/Vendor
                        </a>
                        
                        <div class="text-xs font-bold text-slate-600 pt-1">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-[#9E007E] hover:text-[#800066] underline decoration-2 transition ml-1">
                                Register here
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/50 p-10 flex flex-col justify-between text-center min-h-[420px] transition duration-300 hover:shadow-white/10">
                    <div class="space-y-4">
                        <h2 class="text-2xl sm:text-3xl font-black text-[#0B318F] leading-tight tracking-tight pt-3">
                            UKLK Staff Login
                        </h2>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed pt-2 px-6">
                            Can only be accessed by UKLK staff
                        </p>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ url('login/admin') }}" 
                           class="w-full bg-[#0B318F] hover:bg-[#072266] active:bg-[#041440] text-white font-bold py-3.5 px-6 rounded-full shadow-md hover:shadow-xl transition duration-200 block text-md tracking-wide transform active:scale-[0.99]">
                            Log in as admin
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>