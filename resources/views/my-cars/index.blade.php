<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Listings') }}
            </h2>
            <a href="{{ route('my-cars.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                + Add Listing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($cars->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No listings yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new car listing.</p>
                    <div class="mt-6">
                        <a href="{{ route('my-cars.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                            + Add Listing
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cars as $car)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="relative">
                                @if($car->primaryImage)
                                    <img src="{{ $car->primaryImage->url }}" alt="" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 px-2 py-1 text-xs rounded-full
                                    {{ $car->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $car->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $car->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($car->status) }}
                                </span>
                                @if($car->is_featured)
                                    <span class="absolute top-2 left-2 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Featured</span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->year }} {{ $car->make }} {{ $car->model }}</h3>
                                <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($car->price, 0) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $car->city }} &middot; {{ number_format($car->mileage) }} km</p>

                                @if($car->status === 'rejected' && $car->rejection_reason)
                                    <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 rounded text-xs text-red-700 dark:text-red-300">
                                        {{ $car->rejection_reason }}
                                    </div>
                                @endif

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex gap-2">
                                        <a href="{{ route('my-cars.edit', $car) }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Edit</a>
                                        <form method="POST" action="{{ route('my-cars.destroy', $car) }}" onsubmit="return confirm('Delete this listing?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-500 font-medium">Delete</button>
                                        </form>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $car->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $cars->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
