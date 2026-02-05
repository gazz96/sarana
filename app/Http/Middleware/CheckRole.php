<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        if (!$user->role) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account does not have a role assigned. Please contact administrator.');
        }

        // Check if user has any of the required roles
        if (!empty($roles) && !$user->hasRole($roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}