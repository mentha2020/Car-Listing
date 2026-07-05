@props(['car'])

<a href="{{ route('cars.show', $car) }}" class="block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100">
    <div class="relative overflow-hidden">
        @if($car->primaryImage)
            <img src="{{ $car->primaryImage->url }}" alt="{{ $car->year }} {{ $car->make }} {{ $car->model }}" class="w-full h-52 object-cover group-hover:scale-110 transition duration-500" loading="lazy" decoding="async">
        @else
            <div class="w-full h-52 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-300">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        @if($car->is_featured)
            <span class="absolute top-3 left-3 px-3 py-1 text-xs bg-[#ebff24] text-black rounded-lg font-bold uppercase tracking-wide">Featured</span>
        @endif
        <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-lg">
            {{ $car->year }}
        </div>
    </div>
    <div class="p-5">
        <div class="flex items-start justify-between gap-2 mb-2">
            <h3 class="font-bold text-gray-900 group-hover:text-[#29f18d] transition text-lg leading-tight">
                {{ $car->make }} {{ $car->model }}
            </h3>
        </div>
        <div class="text-2xl font-extrabold text-gray-900 mb-3">
            ${{ number_format($car->price, 0) }}
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $car->city }}
            </span>
            <span>{{ number_format($car->mileage) }} km</span>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
            <span class="px-2.5 py-1 text-xs rounded-lg bg-gray-100 text-gray-600 font-medium">{{ ucfirst($car->fuel_type) }}</span>
            <span class="px-2.5 py-1 text-xs rounded-lg bg-gray-100 text-gray-600 font-medium">{{ ucfirst($car->transmission) }}</span>
        </div>
    </div>
</a>
