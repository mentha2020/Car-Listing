<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $car->year }} {{ $car->make }} {{ $car->model }}
            </h2>
            <a href="{{ route('admin.cars.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back to list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Car Details --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Images --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Photos</h3>
                        @if($car->images->isNotEmpty())
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($car->images as $image)
                                    <div class="relative">
                                        <img src="{{ $image->url }}" alt="" class="w-full h-40 object-cover rounded-lg">
                                        @if($image->is_primary)
                                            <span class="absolute top-2 left-2 px-2 py-1 text-xs bg-indigo-600 text-white rounded">Primary</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No photos uploaded.</p>
                        @endif
                    </div>

                    {{-- Car Info --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Details</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Make</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->make }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Model</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->model }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Year</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->year }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Price</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">${{ number_format($car->price, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Mileage</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($car->mileage) }} km</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Fuel Type</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($car->fuel_type) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Transmission</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($car->transmission) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">City</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->city }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Views</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->views_count }}</div>
                            </div>
                        </div>

                        @if($car->description)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Description</div>
                                <p class="text-gray-900 dark:text-gray-100">{{ $car->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Status --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Status</h3>
                        <div class="mb-4">
                            <span class="px-3 py-1 text-sm rounded-full
                                {{ $car->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $car->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                {{ $car->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                {{ ucfirst($car->status) }}
                            </span>
                        </div>

                        @if($car->rejection_reason)
                            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg mb-4">
                                <div class="text-sm font-medium text-red-800 dark:text-red-200 mb-1">Rejection Reason:</div>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ $car->rejection_reason }}</p>
                            </div>
                        @endif

                        @if($car->is_featured)
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg mb-4">
                                <div class="text-sm text-yellow-700 dark:text-yellow-300">Featured until {{ $car->featured_until->format('M d, Y') }}</div>
                            </div>
                        @endif

                        {{-- Approve / Reject --}}
                        @if($car->status === 'pending')
                            <div class="space-y-3">
                                <form method="POST" action="{{ route('admin.cars.approve', $car) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 transition">
                                        Approve Listing
                                    </button>
                                </form>

                                <div x-data="{ showReject: false }">
                                    <button @click="showReject = !showReject" class="w-full px-4 py-2 bg-red-600 text-white rounded-md font-medium hover:bg-red-700 transition">
                                        Reject Listing
                                    </button>
                                    <div x-show="showReject" x-cloak class="mt-3">
                                        <form method="POST" action="{{ route('admin.cars.reject', $car) }}">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="rejection_reason" rows="3" required placeholder="Reason for rejection..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm mb-2"></textarea>
                                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">Submit Rejection</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Seller Info --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Seller</h3>
                        <div class="space-y-2">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Name</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->user->name }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->user->email }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Member Since</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->user->created_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total Listings</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $car->user->cars()->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
