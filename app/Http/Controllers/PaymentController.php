<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function showPlans(Car $car): View
    {
        abort_unless($car->user_id === auth()->id(), 403);
        abort_unless($car->isApproved(), 400);

        $plans = [
            ['name' => 'Featured', 'price' => 5, 'days' => 7, 'description' => 'Featured badge for 7 days'],
            ['name' => 'Premium Featured', 'price' => 10, 'days' => 30, 'description' => 'Featured badge for 30 days + top of results'],
        ];

        return view('payments.plans', compact('car', 'plans'));
    }

    public function checkout(Request $request, Car $car): RedirectResponse
    {
        abort_unless($car->user_id === auth()->id(), 403);
        abort_unless($car->isApproved(), 400);

        $request->validate([
            'plan' => 'required|in:featured,premium',
        ]);

        $plan = match($request->plan) {
            'featured' => ['price' => 500, 'days' => 7, 'type' => 'featured'],
            'premium' => ['price' => 1000, 'days' => 30, 'type' => 'featured'],
        };

        $payment = $this->paymentService->createPayment($car, $plan);

        $session = $this->paymentService->createCheckoutSession($payment, $car);

        if ($session && $session->url) {
            return redirect($session->url);
        }

        return back()->with('error', 'Unable to create payment session. Please try again.');
    }

    public function success(Request $request): RedirectResponse
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            $this->paymentService->handleSuccess($sessionId);
        }

        return redirect()->route('my-cars.index')
            ->with('success', 'Payment successful! Your listing is now featured.');
    }

    public function cancel(Car $car): RedirectResponse
    {
        return redirect()->route('payments.plans', $car)
            ->with('error', 'Payment was cancelled.');
    }
}
