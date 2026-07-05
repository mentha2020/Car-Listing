<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search Results - {{ config('app.name', 'CarListing') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">

    {{-- NAVIGATION --}}
    <nav class="bg-black sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-white text-xl font-extrabold tracking-tight">
                    Car<span class="text-[#29f18d]">Listing</span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium text-sm">Home</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium text-sm">Dashboard</a>
                        @if(auth()->user()->isClient())
                            <a href="{{ route('my-cars.index') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium text-sm">My Listings</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-white/80 hover:text-[#29f18d] transition font-medium text-sm">Login</a>
                    @endauth
                </div>
                <button @click="open = !open" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- SEARCH BAR --}}
    <div class="bg-white border-b sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="GET" action="/" class="flex gap-3">
                <div class="flex-1 flex items-center bg-gray-100 rounded-xl px-4">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full py-3 bg-transparent focus:outline-none ml-3">
                </div>
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shrink-0 text-sm">Search</button>
            </form>
        </div>
    </div>

    {{-- FILTERS + RESULTS --}}
    <div class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Filters Sidebar --}}
                <div class="lg:w-72 shrink-0" x-data="{ show: false }">
                    <button @click="show = !show" class="lg:hidden w-full bg-white rounded-xl p-4 flex items-center justify-between font-bold text-gray-900 mb-4 shadow-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filters
                        </span>
                        <svg :class="show ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div :class="show ? 'block' : 'hidden lg:block'" class="bg-white rounded-2xl p-6 shadow-sm">
                        <form method="GET" action="/" class="space-y-5">
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Make</label>
                                <select name="make" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]" onchange="this.form.submit()">
                                    <option value="">All Makes</option>
                                    @foreach($makes as $make)
                                        <option value="{{ $make }}" {{ request('make') === $make ? 'selected' : '' }}>{{ $make }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Year Range</label>
                                <div class="flex gap-2">
                                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From" min="1900" max="{{ date('Y') + 1 }}" class="w-1/2 rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]">
                                    <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="To" min="1900" max="{{ date('Y') + 1 }}" class="w-1/2 rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Price Range ($)</label>
                                <div class="flex gap-2">
                                    <input type="number" name="price_from" value="{{ request('price_from') }}" placeholder="Min" min="0" class="w-1/2 rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]">
                                    <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="Max" min="0" class="w-1/2 rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Fuel Type</label>
                                <select name="fuel_type" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]" onchange="this.form.submit()">
                                    <option value="">All Fuel Types</option>
                                    <option value="petrol" {{ request('fuel_type') === 'petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="diesel" {{ request('fuel_type') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="electric" {{ request('fuel_type') === 'electric' ? 'selected' : '' }}>Electric</option>
                                    <option value="hybrid" {{ request('fuel_type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Transmission</label>
                                <select name="transmission" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]" onchange="this.form.submit()">
                                    <option value="">All Transmissions</option>
                                    <option value="manual" {{ request('transmission') === 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatic" {{ request('transmission') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Max Mileage (km)</label>
                                <input type="number" name="mileage_to" value="{{ request('mileage_to') }}" placeholder="e.g. 100000" min="0" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                                <select name="city" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-[#29f18d] focus:ring-[#29f18d]" onchange="this.form.submit()">
                                    <option value="">All Cities</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit" class="flex-1 bg-black text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition text-sm">Apply Filters</button>
                                <a href="{{ route('home') }}" class="px-4 py-3 text-gray-500 font-medium hover:text-gray-900 text-sm">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Results --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-gray-600 font-medium">{{ $cars->total() }} cars found</p>
                        <select onchange="window.location.href='?sort='+this.value+'&{{ http_build_query(request()->except('sort')) }}'" class="rounded-xl border-gray-200 bg-white text-sm font-medium focus:border-[#29f18d] focus:ring-[#29f18d]">
                            <option value="newest" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="mileage_low" {{ request('sort') === 'mileage_low' ? 'selected' : '' }}>Mileage: Low to High</option>
                        </select>
                    </div>

                    @if($cars->isEmpty())
                        <div class="bg-white rounded-2xl p-16 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <h3 class="mt-4 text-xl font-bold text-gray-900">No cars found</h3>
                            <p class="mt-2 text-gray-500">Try adjusting your search filters.</p>
                            <a href="{{ route('home') }}" class="mt-6 inline-block bg-[#29f18d] text-black px-6 py-3 rounded-xl font-bold hover:bg-[#22d97a] transition">Browse All Cars</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($cars as $car)
                                <x-car-card :car="$car" />
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $cars->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-[#1F1F1F] text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('home') }}" class="text-xl font-extrabold">
                Car<span class="text-[#29f18d]">Listing</span>
            </a>
            <p class="text-gray-500 text-sm mt-2">&copy; {{ date('Y') }} CarListing. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
