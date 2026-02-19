<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_approved) {
            auth()->logout();

            return redirect(route('login'))->with('error', 'Your account is pending approval. Please contact an administrator.');
        }

        return $next($request);
    }
}
