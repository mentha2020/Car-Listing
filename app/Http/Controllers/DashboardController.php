<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return $this->clientDashboard($user);
    }

    private function adminDashboard($user): View
    {
        $stats = [
            'total_cars' => Car::count(),
            'pending_cars' => Car::pending()->count(),
            'approved_cars' => Car::approved()->count(),
            'rejected_cars' => Car::where('status', 'rejected')->count(),
            'total_users' => \App\Models\User::count(),
            'total_favorites' => \App\Models\Favorite::count(),
            'total_payments' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
        ];

        $pendingCars = Car::pending()->with('user', 'primaryImage')->latest()->take(10)->get();

        return view('dashboard', compact('stats', 'pendingCars'));
    }

    private function clientDashboard($user): View
    {
        $stats = [
            'total_listings' => $user->cars()->count(),
            'approved_listings' => $user->cars()->approved()->count(),
            'pending_listings' => $user->cars()->pending()->count(),
            'rejected_listings' => $user->cars()->where('status', 'rejected')->count(),
            'favorites_count' => $user->favorites()->count(),
            'messages_count' => \App\Models\Message::whereHas('conversation', function ($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })->whereNull('read_at')->where('user_id', '!=', $user->id)->count(),
        ];

        $myCars = $user->cars()->with('primaryImage')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'myCars'));
    }
}
