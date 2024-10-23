<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserActivity;
use Carbon\Carbon;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_activity = Carbon::now();
            $user->save();

            broadcast(new UserActivity($user))->toOthers();
        }

        return $next($request);
    }
}