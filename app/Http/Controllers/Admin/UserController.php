<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('cars');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban an admin user.');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', "User {$user->name} has been banned.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', "User {$user->name} has been unbanned.");
    }
}
