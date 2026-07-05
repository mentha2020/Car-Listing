<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $conversation = $this->message->conversation;
        $sender = $this->message->user;

        return (new MailMessage)
            ->subject('New Message Received')
            ->greeting("Hello {$notifiable->name},")
            ->line("**{$sender->name}** sent you a message regarding the **{$conversation->car->year} {$conversation->car->make} {$conversation->car->model}**.")
            ->line("**Message:** \"{$this->message->body}\"")
            ->action('View Conversation', route('messages.show', $conversation))
            ->line('Thank you for using CarListing!');
    }
}
