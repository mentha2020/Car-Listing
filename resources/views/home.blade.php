<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Cars') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Search Bar --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                <form method="GET" action="/" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search make, model, or keyword..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">Search</button>
                </form>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Filters Sidebar --}}
                <div class="lg:w-72 shrink-0">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6" x-data="{ mobileFilter: false }">
                        <div class="flex items-center justify-between mb-4 lg:hidden">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Filters</h3>
                            <button @click="mobileFilter = !mobileFilter" class="text-indigo-600 text-sm">
                                <span x-text="mobileFilter ? 'Hide' : 'Show'"></span>
                        </button>
                        </div>

                        <form method="GET" action="/" id="filterForm" class="space-y-4" :class="mobileFilter ? 'block' : 'hidden lg:block'">
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Make</label>
                                <select name="make" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                    <option value="">All Makes</option>
                                    @foreach($makes as $make)
                                        <option value="{{ $make }}" {{ request('make') === $make ? 'selected' : '' }}>{{ $make }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                                <div class="flex gap-2">
                                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From" min="1900" max="{{ date('Y') + 1 }}" class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                                    <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="To" min="1900" max="{{ date('Y') + 1 }}" class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price Range</label>
                                <div class="flex gap-2">
                                    <input type="number" name="price_from" value="{{ request('price_from') }}" placeholder="Min" min="0" class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                                    <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="Max" min="0" class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fuel Type</label>
                                <select name="fuel_type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                    <option value="">All Fuel Types</option>
                                    <option value="petrol" {{ request('fuel_type') === 'petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="diesel" {{ request('fuel_type') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="electric" {{ request('fuel_type') === 'electric' ? 'selected' : '' }}>Electric</option>
                                    <option value="hybrid" {{ request('fuel_type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transmission</label>
                                <select name="transmission" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                    <option value="">All Transmissions</option>
                                    <option value="manual" {{ request('transmission') === 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatic" {{ request('transmission') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Mileage</label>
                                <input type="number" name="mileage_to" value="{{ request('mileage_to') }}" placeholder="e.g. 100000" min="0" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                                <select name="city" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                    <option value="">All Cities</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition">Apply</button>
                                <a href="/" class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm font-medium hover:text-gray-900 dark:hover:text-gray-100">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Results --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cars->total() }} cars found</p>
                        <select onchange="window.location.href='?'+this.value+'&{{ http_build_query(request()->except('sort')) }}'" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                            <option value="sort=newest" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Newest First</option>
                            <option value="sort=price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="sort=price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="sort=mileage_low" {{ request('sort') === 'mileage_low' ? 'selected' : '' }}>Mileage: Low to High</option>
                        </select>
                    </div>

                    @if($cars->isEmpty())
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No cars found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search filters.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($cars as $car)
                                <x-car-card :car="$car" />
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $cars->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
