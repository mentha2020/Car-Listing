<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Payment;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPayment(Car $car, array $plan): Payment
    {
        return Payment::create([
            'user_id' => $car->user_id,
            'car_id' => $car->id,
            'amount' => $plan['price'] / 100,
            'type' => $plan['type'],
            'status' => 'pending',
        ]);
    }

    public function createCheckoutSession(Payment $payment, Car $car): ?Session
    {
        try {
            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => "Feature: {$car->year} {$car->make} {$car->model}",
                            'description' => "Feature this listing for {$payment->type}",
                        ],
                        'unit_amount' => (int)($payment->amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payments.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('payments.cancel', $car),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'car_id' => $car->id,
                ],
            ]);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function handleSuccess(string $sessionId): void
    {
        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $paymentId = $session->metadata->payment_id ?? null;
                $carId = $session->metadata->car_id ?? null;

                if ($paymentId) {
                    $payment = Payment::find($paymentId);
                    if ($payment) {
                        $payment->markAsCompleted();
                        $payment->update(['stripe_payment_id' => $sessionId]);

                        $days = match($payment->type) {
                            'featured' => $payment->amount >= 10 ? 30 : 7,
                            default => 7,
                        };

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
        } catch (\Exception $e) {
            report($e);
        }
    }
}
