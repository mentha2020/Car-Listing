<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ auth()->user()->isAdmin() ? __('Admin Dashboard') : __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isAdmin())
                {{-- ADMIN DASHBOARD --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Cars</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_cars'] }}</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-yellow-200 dark:border-yellow-800">
                        <div class="text-sm text-yellow-600 dark:text-yellow-400">Pending Approval</div>
                        <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['pending_cars'] }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-green-200 dark:border-green-800">
                        <div class="text-sm text-green-600 dark:text-green-400">Approved</div>
                        <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $stats['approved_cars'] }}</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-blue-200 dark:border-blue-800">
                        <div class="text-sm text-blue-600 dark:text-blue-400">Total Users</div>
                        <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total_users'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Pending Approvals</h3>
                        @if($pendingCars->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">No cars pending approval.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($pendingCars as $car)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            @if($car->primaryImage)
                                                <img src="{{ $car->primaryImage->url }}" alt="" class="w-12 h-12 rounded object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->year }} {{ $car->make }} {{ $car->model }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">by {{ $car->user->name }} &middot; ${{ number_format($car->price, 0) }}</div>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.cars.show', $car) }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">Review</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            @else
                {{-- CLIENT DASHBOARD --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">My Listings</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_listings'] }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-green-200 dark:border-green-800">
                        <div class="text-sm text-green-600 dark:text-green-400">Approved</div>
                        <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $stats['approved_listings'] }}</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-yellow-200 dark:border-yellow-800">
                        <div class="text-sm text-yellow-600 dark:text-yellow-400">Pending</div>
                        <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['pending_listings'] }}</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-red-200 dark:border-red-800">
                        <div class="text-sm text-red-600 dark:text-red-400">Rejected</div>
                        <div class="text-3xl font-bold text-red-700 dark:text-red-300">{{ $stats['rejected_listings'] }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Favorites</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['favorites_count'] }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Unread Messages</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['messages_count'] }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-center">
                        <a href="{{ route('my-cars.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            + Add New Listing
                        </a>
                    </div>
                </div>

                @if(isset($myCars) && $myCars->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Listings</h3>
                                <a href="{{ route('my-cars.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">View All</a>
                            </div>
                            <div class="space-y-3">
                                @foreach($myCars as $car)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            @if($car->primaryImage)
                                                <img src="{{ $car->primaryImage->url }}" alt="" class="w-12 h-12 rounded object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->year }} {{ $car->make }} {{ $car->model }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($car->price, 0) }} &middot; {{ $car->city }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $car->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $car->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $car->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                                {{ ucfirst($car->status) }}
                                            </span>
                                            <a href="{{ route('my-cars.edit', $car) }}" class="text-sm text-indigo-600 hover:text-indigo-500">Edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
