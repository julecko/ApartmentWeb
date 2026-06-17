<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\AdminAccount;
use App\Models\NewsItem;
use App\Http\Resources\NewsItemResource;

class AdminController extends Controller
{
    public function loginPage()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.index');
        }

        return Inertia::render('LoginPage');
    }

    public function login(Request $request)
    {
        $key = 'admin-login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'username' => "Too many attempts. Try again in {$seconds}s.",
            ]);
        }

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = AdminAccount::where('username', $request->input('username'))->first();

        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'username' => 'Invalid credentials.',
            ]);
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_authenticated_at', now()->toIso8601String());

        return redirect()->route('admin.index');
    }

    public function index()
    {
        $items = NewsItem::with('attachments')
            ->orderByDesc('pinned')
            ->orderByDesc('date')
            ->get();

        return Inertia::render('AdminPanel', [
            'items' => NewsItemResource::collection($items),
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_authenticated',
            'admin_authenticated_at',
        ]);

        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }
}
