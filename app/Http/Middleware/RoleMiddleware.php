<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if(!Auth::check()) {
            return redirect('/login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        if(!in_array($user->type, $types)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}