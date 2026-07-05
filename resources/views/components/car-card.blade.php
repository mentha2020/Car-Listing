@props(['car'])

<a href="{{ route('cars.show', $car) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition group">
    <div class="relative">
        @if($car->primaryImage)
            <img src="{{ $car->primaryImage->url }}" alt="{{ $car->year }} {{ $car->make }} {{ $car->model }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" loading="lazy">
        @else
            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        @if($car->is_featured)
            <span class="absolute top-2 left-2 px-2 py-1 text-xs bg-yellow-500 text-white rounded-full font-medium">Featured</span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
            {{ $car->year }} {{ $car->make }} {{ $car->model }}
        </h3>
        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 mt-1">${{ number_format($car->price, 0) }}</p>
        <div class="flex items-center gap-3 mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $car->city }}
            </span>
            <span>&middot;</span>
            <span>{{ number_format($car->mileage) }} km</span>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ ucfirst($car->fuel_type) }}</span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ ucfirst($car->transmission) }}</span>
        </div>
    </div>
</a>
