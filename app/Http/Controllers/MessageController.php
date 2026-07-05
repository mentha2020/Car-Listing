<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = Conversation::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })
        ->with(['car', 'sender', 'receiver', 'lastMessage'])
        ->orderByDesc('updated_at')
        ->get();

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $user = auth()->user();
        abort_unless(
            $conversation->sender_id === $user->id || $conversation->receiver_id === $user->id,
            403
        );

        $conversation->load(['car', 'sender', 'receiver']);
        $messages = $conversation->messages()->with('user')->get();

        // Mark unread messages as read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $conversation->getOtherUser($user);

        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(Request $request, Car $car, User $seller): RedirectResponse
    {
        $user = $request->user();
        abort_if($user->id === $seller->id, 400);

        $conversation = Conversation::firstOrCreate(
            [
                'car_id' => $car->id,
                'sender_id' => $user->id,
                'receiver_id' => $seller->id,
            ]
        );

        if ($request->body) {
            $conversation->messages()->create([
                'user_id' => $user->id,
                'body' => $request->body,
            ]);
            $conversation->touch();
        }

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Conversation started!');
    }

    public function send(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = $request->user();
        abort_unless(
            $conversation->sender_id === $user->id || $conversation->receiver_id === $user->id,
            403
        );

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        $conversation->touch();

        return back();
    }
}
