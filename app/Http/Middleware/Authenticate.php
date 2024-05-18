<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Controller
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('login');
    // }

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $this->sendError('Unauthorized', [],401);
        }

        return $next($request);
    }
}
