<x-app-layout>
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="text-center">
            <div class="text-8xl font-bold text-red-600 dark:text-red-400">403</div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-4">Access Denied</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">You don't have permission to access this page.</p>
            <a href="{{ route('home') }}" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition">
                Back to Home
            </a>
        </div>
    </div>
</x-app-layout>
