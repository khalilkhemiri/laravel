<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Events\UserActivity;
use App\Events\UserOffline;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        event(new UserActivity($user)); // Pass the entire user object

        if ($user->role == 'admin') {
            return redirect('/admin');  
        } else {
            return redirect('/');  
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    $user = Auth::user();

    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    if ($user) {
        broadcast(new UserOffline($user))->toOthers(); 
    }

    return redirect('/');
}



public function updateActivity()
{
    if (Auth::check()) {
        $user = Auth::user();
        $user->last_activity = now();
        $user->save();
    }

    return response()->json(['status' => 'success']);
}


}
