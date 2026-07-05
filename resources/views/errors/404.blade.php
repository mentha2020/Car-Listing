<x-app-layout>
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="text-center">
            <div class="text-8xl font-bold text-indigo-600 dark:text-indigo-400">404</div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-4">Page Not Found</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">The page you're looking for doesn't exist or has been moved.</p>
            <a href="{{ route('home') }}" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition">
                Back to Home
            </a>
        </div>
    </div>
</x-app-layout>
