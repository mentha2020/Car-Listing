<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">&larr;</a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $otherUser->name }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $conversation->car->year }} {{ $conversation->car->make }} {{ $conversation->car->model }}</p>
                </div>
            </div>
            <a href="{{ route('cars.show', $conversation->car) }}" class="text-sm text-indigo-600 hover:text-indigo-500">View Listing</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Messages --}}
                <div class="h-[500px] overflow-y-auto p-6 space-y-4" id="messages-container">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                <p class="text-sm">{{ $message->body }}</p>
                                <p class="text-xs mt-1 {{ $message->user_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $message->created_at->format('H:i') }}
                                    @if($message->user_id === auth()->id())
                                        @if($message->isRead())
                                            <span>&check;&check;</span>
                                        @else
                                            <span>&check;</span>
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                {{-- Send Form --}}
                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form method="POST" action="{{ route('messages.send', $conversation) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="body" required placeholder="Type your message..." maxlength="1000" class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const container = document.getElementById('messages-container');
        if (container) container.scrollTop = container.scrollHeight;
    </script>
    @endpush
</x-app-layout>
