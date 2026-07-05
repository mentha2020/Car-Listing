<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if (!$secret) {
            return response('Webhook secret not configured.', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $this->handleCheckoutCompleted($session);
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $paymentId = $session->metadata->payment_id ?? null;
        $carId = $session->metadata->car_id ?? null;

        if (!$paymentId) {
            return;
        }

        $payment = Payment::find($paymentId);
        if (!$payment || $payment->status === 'completed') {
            return;
        }

        $payment->markAsCompleted();
        $payment->update(['stripe_payment_id' => $session->id]);

        $days = match ($payment->type) {
            'featured' => $payment->amount >= 10 ? 30 : 7,
            default => 7,
        };

        if ($carId) {
            $car = Car::find($carId);
            if ($car) {
                $car->update([
                    'is_featured' => true,
                    'featured_at' => now(),
                    'featured_until' => now()->addDays($days),
                ]);
            }
        }
    }
}
