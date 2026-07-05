<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusCtx = document.getElementById('statusChart')?.getContext('2d');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Approved', 'Rejected', 'Draft'],
                        datasets: [{
                            data: @json($carsByStatus),
                            backgroundColor: ['#f59e0b', '#10b981', '#ef4444', '#6b7280'],
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                });
            }

            const weeklyCtx = document.getElementById('weeklyChart')?.getContext('2d');
            if (weeklyCtx) {
                new Chart(weeklyCtx, {
                    type: 'line',
                    data: {
                        labels: @json($weeklyCars->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
                        datasets: [{
                            label: 'Cars Listed',
                            data: @json($weeklyCars->pluck('count')),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,0.1)',
                            fill: true,
                            tension: 0.3,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            }
        });
    </script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Cars</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_cars'] }}</div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-yellow-200 dark:border-yellow-800">
                    <div class="text-sm text-yellow-600 dark:text-yellow-400">Pending</div>
                    <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['pending_cars'] }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-green-200 dark:border-green-800">
                    <div class="text-sm text-green-600 dark:text-green-400">Approved</div>
                    <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $stats['approved_cars'] }}</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-blue-200 dark:border-blue-800">
                    <div class="text-sm text-blue-600 dark:text-blue-400">Users</div>
                    <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total_users'] }}</div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cars by Status</h3>
                    <div style="height: 250px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Weekly Listings</h3>
                    <div style="height: 250px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Revenue</h3>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">${{ number_format($stats['total_revenue'], 2) }}</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">From premium listings</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Cities</h3>
                    @forelse($topCities as $city)
                        <div class="flex items-center justify-between py-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $city->city }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $city->count }} listings</span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No data yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Cars --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Listings</h3>
                        <a href="{{ route('admin.cars.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Car</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Seller</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($recentCars as $car)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                @if($car->primaryImage)
                                                    <img src="{{ $car->primaryImage->url }}" class="w-10 h-10 rounded object-cover">
                                                @else
                                                    <div class="w-10 h-10 rounded bg-gray-200 dark:bg-gray-600"></div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $car->year }} {{ $car->make }} {{ $car->model }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $car->user->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${{ number_format($car->price, 0) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $car->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $car->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $car->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                                {{ ucfirst($car->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $car->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.cars.show', $car) }}" class="text-indigo-600 hover:text-indigo-500">Review</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
