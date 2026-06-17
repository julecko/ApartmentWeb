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

            return response()->json([
                'success' => false,
                'message' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = AdminAccount::where('username', $request->input('username'))->first();

        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
            RateLimiter::hit($key, 60);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_authenticated_at', now()->toIso8601String());

        return redirect()->route('admin.index');
    }

    public function index()
    {
        return Inertia::render('AdminPanel', [
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
