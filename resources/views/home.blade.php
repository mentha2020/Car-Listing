<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CarListing') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-gradient { background: linear-gradient(to bottom, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.1) 60%, rgba(0,0,0,0) 100%); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    {{-- NAVIGATION --}}
    <nav class="absolute w-full z-50 p-4 transition-all duration-300" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-white text-2xl font-extrabold tracking-tight">
                Car<span class="text-[#29f18d]">Listing</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium">Home</a>
                <a href="{{ route('home') }}?fuel_type=electric" class="text-white/80 hover:text-[#29f18d] transition font-medium">Electric</a>
                <a href="{{ route('home') }}?fuel_type=petrol" class="text-white/80 hover:text-[#29f18d] transition font-medium">Petrol</a>
                <a href="{{ route('home') }}?fuel_type=diesel" class="text-white/80 hover:text-[#29f18d] transition font-medium">Diesel</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium">Dashboard</a>
                    @if(auth()->user()->isClient())
                        <a href="{{ route('my-cars.index') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium">My Listings</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium">Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-[#29f18d] text-black px-5 py-2 rounded-xl font-bold hover:bg-[#22d97a] transition">Register</a>
                @endauth
            </div>

            <button @click="open = !open" class="md:hidden text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden mt-4 bg-black/95 backdrop-blur-sm rounded-2xl p-6 space-y-4">
            <a href="{{ route('home') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Home</a>
            <a href="{{ route('home') }}?fuel_type=electric" class="block text-white hover:text-[#29f18d] transition font-medium">Electric</a>
            <a href="{{ route('home') }}?fuel_type=petrol" class="block text-white hover:text-[#29f18d] transition font-medium">Petrol</a>
            <a href="{{ route('home') }}?fuel_type=diesel" class="block text-white hover:text-[#29f18d] transition font-medium">Diesel</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Dashboard</a>
                @if(auth()->user()->isClient())
                    <a href="{{ route('my-cars.index') }}" class="block text-white hover:text-[#29f18d] transition font-medium">My Listings</a>
                    <a href="{{ route('favorites.index') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Favorites</a>
                    <a href="{{ route('messages.index') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Messages</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-white/60 hover:text-red-400 transition font-medium">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-white hover:text-[#29f18d] transition font-medium">Login</a>
                <a href="{{ route('register') }}" class="block bg-[#29f18d] text-black text-center px-5 py-2.5 rounded-xl font-bold hover:bg-[#22d97a] transition">Register</a>
            @endauth
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative h-[85vh] bg-gray-900 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1492144534653-40032b348272?w=1920&q=80" alt="Car on road" class="absolute inset-0 w-full h-full object-cover">
        <div class="hero-gradient absolute inset-0"></div>

        <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <h1 class="text-white">
                <span class="block text-5xl sm:text-6xl lg:text-8xl font-extrabold leading-tight">Looking for a</span>
                <span class="block text-5xl sm:text-6xl lg:text-8xl font-extrabold leading-tight">new <span class="text-[#29f18d]">ride?</span></span>
            </h1>
            <div class="mt-8 flex flex-col sm:flex-row gap-4 max-w-md">
                <a href="#search" class="bg-[#29f18d] text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#22d97a] transition flex items-center justify-center gap-2">
                    Find a car
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('my-cars.create') }}" class="bg-[#ebff24] text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#d4e620] transition flex items-center justify-center gap-2">
                            Sell my car
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="bg-[#ebff24] text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#d4e620] transition flex items-center justify-center gap-2">
                        Sell my car
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- SEARCH BAR --}}
    <section id="search" class="relative z-20 -mt-12 mb-8 px-4 sm:px-0">
        <div class="max-w-5xl mx-auto">
            <form method="GET" action="/" class="bg-white rounded-2xl shadow-xl p-2 flex items-center gap-2">
                <div class="flex-1 flex items-center gap-3 px-4">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" placeholder="Search by car, model or keyword..." class="w-full py-4 text-gray-900 placeholder-gray-400 focus:outline-none bg-transparent">
                </div>
                <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition shrink-0">
                    <span class="hidden sm:inline">Search</span>
                    <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
        </div>
    </section>

    {{-- QUICK FILTER PILLS --}}
    <section class="max-w-5xl mx-auto px-4 mb-12">
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
            <a href="{{ route('home') }}?fuel_type=electric" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2v11h3v9l7-12h-4l4-8z"/></svg>
                Electric
            </a>
            <a href="{{ route('home') }}?fuel_type=petrol" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.77 7.23l.01-.01-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33 0 1.38 1.12 2.5 2.5 2.5.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6C4.9 3 4 3.9 4 5v16h8v-7h2v3.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V9c0-.69-.28-1.32-.73-1.77zM12 10H6V5h6v5z"/></svg>
                Petrol
            </a>
            <a href="{{ route('home') }}?fuel_type=diesel" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.77 7.23l.01-.01-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33 0 1.38 1.12 2.5 2.5 2.5.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6C4.9 3 4 3.9 4 5v16h8v-7h2v3.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V9c0-.69-.28-1.32-.73-1.77zM12 10H6V5h6v5z"/></svg>
                Diesel
            </a>
            <a href="{{ route('home') }}?transmission=automatic" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-4-4 1.41-1.41L11 14.17l6.59-6.59L19 9l-8 8z"/></svg>
                Automatic
            </a>
            <a href="{{ route('home') }}?transmission=manual" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-4-4 1.41-1.41L11 14.17l6.59-6.59L19 9l-8 8z"/></svg>
                Manual
            </a>
            <a href="{{ route('home') }}?fuel_type=hybrid" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-5 py-3 font-bold text-gray-900 transition whitespace-nowrap shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2v11h3v9l7-12h-4l4-8z"/></svg>
                Hybrid
            </a>
        </div>
    </section>

    {{-- STATS BAR --}}
    <section class="max-w-5xl mx-auto px-4 mb-12">
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-2xl p-5 text-center">
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_cars']) }}+</div>
                <div class="text-sm text-gray-500 mt-1">Cars Listed</div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-5 text-center">
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_users']) }}+</div>
                <div class="text-sm text-gray-500 mt-1">Happy Customers</div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-5 text-center">
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_cities']) }}+</div>
                <div class="text-sm text-gray-500 mt-1">Cities</div>
            </div>
        </div>
    </section>

    {{-- FEATURED CARS --}}
    @if($featuredCars->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Featured Cars</h2>
                <p class="text-gray-500 mt-1">Hand-picked premium listings</p>
            </div>
            <a href="{{ route('home') }}?sort=newest" class="text-[#29f18d] hover:text-[#22d97a] font-bold flex items-center gap-1 transition">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredCars as $car)
                <x-car-card :car="$car" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- LATEST CARS --}}
    @if($latestCars->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Latest Cars</h2>
                <p class="text-gray-500 mt-1">Fresh listings added recently</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestCars as $car)
                <x-car-card :car="$car" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- POPULAR MAKES --}}
    <section class="bg-gray-50 py-16 mb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-10">Popular Brands</h2>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($makes->take(12) as $make)
                    <a href="{{ route('home') }}?make={{ urlencode($make) }}" class="bg-white hover:bg-[#29f18d] hover:text-black border-2 border-gray-200 hover:border-[#29f18d] rounded-xl px-6 py-3 font-bold text-gray-700 transition">
                        {{ $make }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-black rounded-3xl p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-white">
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-2">Want to sell your car?</h2>
                <p class="text-gray-400 text-lg">List it today and reach thousands of potential buyers.</p>
            </div>
            @auth
                @if(auth()->user()->isClient())
                    <a href="{{ route('my-cars.create') }}" class="bg-[#29f18d] text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#22d97a] transition flex items-center gap-2 shrink-0">
                        Start Selling
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="bg-[#29f18d] text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#22d97a] transition flex items-center gap-2 shrink-0">
                    Start Selling
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            @endauth
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-[#1F1F1F] text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-start justify-between gap-8 mb-8">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold">
                        Car<span class="text-[#29f18d]">Listing</span>
                    </a>
                    <p class="text-gray-400 mt-2 max-w-xs">Find your perfect car from thousands of verified listings across the country.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <div>
                        <h3 class="font-bold mb-4">Browse</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('home') }}?fuel_type=electric" class="hover:text-[#29f18d] transition">Electric Cars</a></li>
                            <li><a href="{{ route('home') }}?fuel_type=petrol" class="hover:text-[#29f18d] transition">Petrol Cars</a></li>
                            <li><a href="{{ route('home') }}?fuel_type=diesel" class="hover:text-[#29f18d] transition">Diesel Cars</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4">Account</h3>
                        <ul class="space-y-2 text-gray-400">
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="hover:text-[#29f18d] transition">Dashboard</a></li>
                                @if(auth()->user()->isClient())
                                    <li><a href="{{ route('my-cars.create') }}" class="hover:text-[#29f18d] transition">Sell a Car</a></li>
                                    <li><a href="{{ route('favorites.index') }}" class="hover:text-[#29f18d] transition">Favorites</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('login') }}" class="hover:text-[#29f18d] transition">Login</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-[#29f18d] transition">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4">Support</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-[#29f18d] transition">Contact Us</a></li>
                            <li><a href="#" class="hover:text-[#29f18d] transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-[#29f18d] transition">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} CarListing. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
