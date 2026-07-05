<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ isset($car) ? 'Edit Listing' : 'Add New Listing' }}
            </h2>
            <a href="{{ route('my-cars.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back to My Listings</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ isset($car) ? route('my-cars.update', $car) : route('my-cars.store') }}" enctype="multipart/form-data" x-data="carWizard()">
                @csrf
                @if(isset($car))
                    @method('PUT')
                @endif

                {{-- Progress Steps --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                    <div class="flex items-center justify-between">
                        <template x-for="(stepLabel, i) in ['Vehicle Info', 'Pricing', 'Photos', 'Preview']" :key="i">
                            <div class="flex items-center" :class="i < 3 ? 'flex-1' : ''">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                         :class="step > i + 1 ? 'bg-green-500 text-white' : step === i + 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500'">
                                        <span x-show="step <= i + 1" x-text="i + 1"></span>
                                        <svg x-show="step > i + 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-medium hidden sm:inline" :class="step === i + 1 ? 'text-indigo-600' : 'text-gray-500 dark:text-gray-400'" x-text="stepLabel"></span>
                                </div>
                                <div x-show="i < 3" class="flex-1 h-0.5 mx-3 bg-gray-200 dark:bg-gray-700">
                                    <div class="h-full bg-indigo-600 transition-all" :style="`width: ${step > i + 1 ? '100' : '0'}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Step 1: Vehicle Info --}}
                <div x-show="step === 1" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Vehicle Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Make *</label>
                            <select name="make" x-model="form.make" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Make</option>
                                @foreach($makes as $make)
                                    <option value="{{ $make }}">{{ $make }}</option>
                                @endforeach
                            </select>
                            @error('make') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model *</label>
                            <input type="text" name="model" x-model="form.model" required placeholder="e.g. Camry, Civic, Corolla" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('model') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year *</label>
                            <input type="number" name="year" x-model="form.year" required min="1900" max="{{ date('Y') + 1 }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City *</label>
                            <input type="text" name="city" x-model="form.city" required placeholder="e.g. Colombo, Kandy" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fuel Type *</label>
                            <select name="fuel_type" x-model="form.fuel_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select</option>
                                <option value="petrol">Petrol</option>
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                            @error('fuel_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transmission *</label>
                            <select name="transmission" x-model="form.transmission" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select</option>
                                <option value="manual">Manual</option>
                                <option value="automatic">Automatic</option>
                            </select>
                            @error('transmission') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mileage (km) *</label>
                            <input type="number" name="mileage" x-model="form.mileage" required min="0" placeholder="e.g. 50000" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('mileage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" @click="step = 2" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">
                            Next: Pricing &rarr;
                        </button>
                    </div>
                </div>

                {{-- Step 2: Pricing & Description --}}
                <div x-show="step === 2" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pricing & Description</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price (USD) *</label>
                        <input type="number" name="price" x-model="form.price" required min="1" step="0.01" placeholder="e.g. 15000" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" x-model="form.description" rows="5" placeholder="Describe your car, its condition, features, service history..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><span x-text="form.description ? form.description.length : 0"></span>/2000 characters</p>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" @click="step = 1" class="px-6 py-2 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-gray-100">
                            &larr; Back
                        </button>
                        <button type="button" @click="step = 3" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">
                            Next: Photos &rarr;
                        </button>
                    </div>
                </div>

                {{-- Step 3: Photos --}}
                <div x-show="step === 3" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Photos</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Upload up to 10 photos. First photo will be the main image. Max 5MB per image.</p>

                    {{-- Existing Images (edit mode) --}}
                    @if(isset($car) && $car->images->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            @foreach($car->images as $image)
                                <div class="relative group">
                                    <img src="{{ $image->url }}" class="w-full h-28 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                                        @if(!$image->is_primary)
                                            <form method="POST" action="{{ route('my-cars.set-primary', [$car, $image]) }}">
                                                @csrf
                                                <button type="submit" class="text-xs bg-white text-gray-800 px-2 py-1 rounded">Set Primary</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('my-cars.remove-image', [$car, $image]) }}" onsubmit="return confirm('Remove this image?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-red-600 text-white px-2 py-1 rounded">Remove</button>
                                        </form>
                                    </div>
                                    @if($image->is_primary)
                                        <span class="absolute top-1 left-1 px-1.5 py-0.5 text-xs bg-indigo-600 text-white rounded">Primary</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Upload Zone --}}
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-indigo-400 transition"
                         x-on:click="$refs.fileInput.click()"
                         x-on:dragover.prevent="dragging = true"
                         x-on:dragleave.prevent="dragging = false"
                         x-on:drop.prevent="dragging = false; handleFiles($event.dataTransfer.files)"
                         :class="dragging ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : ''">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Drag and drop photos here, or click to browse</p>
                        <input type="file" name="images[]" x-ref="fileInput" multiple accept="image/*" class="hidden" x-on:change="handleFiles($event.target.files)">
                    </div>

                    {{-- Preview --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3" x-show="previews.length > 0">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative">
                                <img :src="preview" class="w-full h-28 object-cover rounded-lg">
                                <button type="button" @click="removePreview(index)" class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-700">&times;</button>
                                <span x-show="index === 0" class="absolute top-1 left-1 px-1.5 py-0.5 text-xs bg-indigo-600 text-white rounded">Primary</span>
                            </div>
                        </template>
                    </div>

                    @error('images') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    @error('images.*') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                    <div class="flex justify-between pt-4">
                        <button type="button" @click="step = 2" class="px-6 py-2 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-gray-100">
                            &larr; Back
                        </button>
                        <button type="button" @click="step = 4" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">
                            Next: Preview &rarr;
                        </button>
                    </div>
                </div>

                {{-- Step 4: Preview & Submit --}}
                <div x-show="step === 4" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Preview Your Listing</h3>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Make:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.make"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Model:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.model"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Year:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.year"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Price:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="'$' + Number(form.price).toLocaleString()"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Mileage:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="Number(form.mileage).toLocaleString() + ' km'"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Fuel:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.fuel_type"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Transmission:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.transmission"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">City:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="form.city"></span></div>
                            <div><span class="text-sm text-gray-500 dark:text-gray-400">Photos:</span> <span class="font-medium text-gray-900 dark:text-gray-100" x-text="previews.length + ' uploaded'"></span></div>
                        </div>
                        <div x-show="form.description" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Description:</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100 text-sm" x-text="form.description"></p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            <strong>Note:</strong> Your listing will be submitted for review. It will appear on the site after an admin approves it.
                        </p>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" @click="step = 3" class="px-6 py-2 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-gray-100">
                            &larr; Back
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 transition">
                            {{ isset($car) ? 'Update Listing' : 'Submit for Review' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function carWizard() {
            return {
                step: 1,
                dragging: false,
                previews: [],
                form: {
                    make: '{{ $car->make ?? '' }}',
                    model: '{{ $car->model ?? '' }}',
                    year: '{{ $car->year ?? '' }}',
                    fuel_type: '{{ $car->fuel_type ?? '' }}',
                    transmission: '{{ $car->transmission ?? '' }}',
                    mileage: '{{ $car->mileage ?? '' }}',
                    city: '{{ $car->city ?? '' }}',
                    price: '{{ $car->price ?? '' }}',
                    description: '{!! addslashes($car->description ?? '') !!}',
                },
                handleFiles(files) {
                    for (let i = 0; i < files.length && this.previews.length < 10; i++) {
                        if (files[i].type.startsWith('image/') && files[i].size <= 5 * 1024 * 1024) {
                            this.previews.push(URL.createObjectURL(files[i]));
                        }
                    }
                },
                removePreview(index) {
                    this.previews.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
