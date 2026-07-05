<?php

namespace App\Notifications;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Car $car) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Car Listing Was Not Approved')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your listing for the **{$this->car->year} {$this->car->make} {$this->car->model}** was not approved.")
            ->line("**Reason:** {$this->car->rejection_reason}")
            ->line('Please review and update your listing, then resubmit for approval.')
            ->action('Edit Listing', route('my-cars.edit', $this->car))
            ->line('Thank you for using CarListing!');
    }
}
