<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($conversations->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No messages yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contact a seller to start a conversation.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($conversations as $conversation)
                        @php
                            $otherUser = $conversation->getOtherUser(auth()->user());
                            $lastMsg = $conversation->lastMessage;
                            $unreadCount = $conversation->messages()->where('user_id', '!=', auth()->id())->whereNull('read_at')->count();
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $unreadCount > 0 ? 'bg-indigo-50 dark:bg-indigo-900/10' : '' }}">
                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold shrink-0">
                                {{ substr($otherUser->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900 dark:text-gray-100 {{ $unreadCount > 0 ? 'font-bold' : '' }}">{{ $otherUser->name }}</span>
                                    @if($lastMsg)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $lastMsg->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $car->year ?? '' }} {{ $car->make ?? '' }} {{ $car->model ?? '' }} &middot;
                                    @if($lastMsg)
                                        {{ Str::limit($lastMsg->body, 50) }}
                                    @else
                                        No messages yet
                                    @endif
                                </div>
                            </div>
                            @if($unreadCount > 0)
                                <span class="px-2 py-1 text-xs bg-indigo-600 text-white rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
