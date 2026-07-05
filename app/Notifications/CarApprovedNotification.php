<?php

namespace App\Notifications;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Your Car Listing Has Been Approved!')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your listing for the **{$this->car->year} {$this->car->make} {$this->car->model}** has been approved and is now live on the site.")
            ->action('View Listing', route('cars.show', $this->car))
            ->line('Thank you for using CarListing!');
    }
}
