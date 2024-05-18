<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        $token = ($request->header('Authorization'))? $request->header('Authorization'):$request['Authorization'];
        
       if(!$token) {
            return $this->sendError('Token missing', '',401);
        }
        $token = str_replace('Bearer ', '', $token);

        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return $this->sendError('Invalid token', '',401);
        }

        if($user->api_token_expire_at < Carbon::now()){
            return $this->sendError('Token expired', '',401);
        }


        return $next($request);
    }
}
