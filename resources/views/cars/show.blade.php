<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $car->year }} {{ $car->make }} {{ $car->model }}
                        </h1>
                        @if($car->is_featured)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full text-sm font-medium">Featured</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            @if($car->images->count())
                                <img src="{{ optional($car->primaryImage)->url ?? $car->images->first()->url }}" alt="{{ $car->year }} {{ $car->make }} {{ $car->model }}" class="w-full h-96 object-cover rounded-lg">
                                @if($car->images->count() > 1)
                                    <div class="flex gap-2 mt-4 overflow-x-auto">
                                        @foreach($car->images as $image)
                                            <img src="{{ $image->url }}" alt="" class="w-20 h-20 object-cover rounded cursor-pointer border-2 {{ $image->is_primary ? 'border-indigo-500' : 'border-transparent' }}">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">${{ number_format($car->price, 0) }}</div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Year</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->year }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Mileage</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($car->mileage) }} km</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Fuel</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($car->fuel_type) }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Transmission</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($car->transmission) }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">City</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->city }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Views</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->views_count }}</div>
                                </div>
                            </div>

                            @auth
                                @if(auth()->id() !== $car->user_id)
                                    <form method="POST" action="{{ route('messages.create', [$car, $car->user]) }}" class="mb-4">
                                        @csrf
                                        <textarea name="body" rows="2" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm" placeholder="Message the seller..."></textarea>
                                        <button type="submit" class="mt-2 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition">Contact Seller</button>
                                    </form>
                                @endif
                            @endauth

                            @php
                                $favoriteClass = $isFavorited ? 'bg-red-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-red-100';
                            @endphp
                            @auth
                                @if(auth()->id() !== $car->user_id)
                                    <form method="POST" action="{{ route('favorites.toggle', $car) }}" class="mb-4">
                                        @csrf
                                        <button type="submit" class="w-full {{ $favoriteClass }} font-medium py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            {{ $isFavorited ? 'Favorited' : 'Add to Favorites' }}
                                        </button>
                                    </form>
                                @endif
                            @endauth

                            @if(auth()->id() === $car->user_id)
                                <a href="{{ route('my-cars.edit', $car) }}" class="block w-full text-center bg-gray-800 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition">Edit Listing</a>
                            @endif
                        </div>
                    </div>

                    @if($car->description)
                        <div class="mt-8">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h2>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $car->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($relatedCars->count())
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Related Cars</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($relatedCars as $related)
                            <x-car-card :car="$related" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    @php
    $jsonLd = [
        "@context" => "https://schema.org",
        "@type" => "Car",
        "name" => $car->year . " " . $car->make . " " . $car->model,
        "brand" => ["@type" => "Brand", "name" => $car->make],
        "model" => $car->model,
        "vehicleModelDate" => $car->year,
        "price" => ["@type" => "MonetaryAmount", "price" => $car->price, "priceCurrency" => "USD"],
        "mileageFromOdometer" => ["@type" => "QuantitativeValue", "value" => $car->mileage, "unitCode" => "KMT"],
        "fuelType" => $car->fuel_type,
        "vehicleTransmission" => $car->transmission,
        "address" => ["@type" => "PostalAddress", "addressLocality" => $car->city],
        "image" => optional($car->primaryImage)->url,
        "description" => $car->description,
    ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush
</x-app-layout>
