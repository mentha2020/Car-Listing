<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Feature Your Listing
            </h2>
            <a href="{{ route('my-cars.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Car Preview --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-center gap-4">
                    @if($car->primaryImage)
                        <img src="{{ $car->primaryImage->url }}" alt="" class="w-20 h-20 rounded-lg object-cover">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->year }} {{ $car->make }} {{ $car->model }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($car->price, 0) }} &middot; {{ $car->city }}</p>
                    </div>
                </div>
            </div>

            {{-- Plans --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plans as $index => $plan)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 {{ $index === 1 ? 'ring-2 ring-indigo-500' : '' }}">
                        @if($index === 1)
                            <span class="inline-block px-2 py-1 text-xs bg-indigo-600 text-white rounded-full mb-2">Recommended</span>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $plan['name'] }}</h3>
                        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 my-4">${{ $plan['price'] }}</div>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $plan['description'] }}</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Featured badge on listing
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Appears first in search results
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan['days'] }} days visibility
                            </li>
                        </ul>
                        <form method="POST" action="{{ route('payments.checkout', $car) }}">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $index === 0 ? 'featured' : 'premium' }}">
                            <button type="submit" class="w-full px-4 py-3 {{ $index === 1 ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-800 hover:bg-gray-700 dark:bg-gray-200 dark:hover:bg-white dark:text-gray-800' }} text-white rounded-md font-medium transition">
                                Pay ${{ $plan['price'] }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
