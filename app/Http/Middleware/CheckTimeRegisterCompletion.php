<?php
// app/Http/Middleware/CheckTimeRegisterCompletion.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Jcr;

class CheckTimeRegisterCompletion
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for specific routes
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        // For JCR creation, we handle it in the controller
        if ($request->routeIs('jcr.create') || $request->routeIs('jcr.store') || $request->routeIs('jcr.edit')) {
            return $next($request);
        }

        // Get the current JCR (you might need to adjust this based on your routing)
        $jcrId = $request->route('jcr') ?? $request->route('id') ?? session('current_jcr_id');
        
        if ($jcrId) {
            $jcr = Jcr::find($jcrId);
            
            if ($jcr && $jcr->requiresTimeRegisterLinking()) {
                // Store intended URL for after modal completion
                session(['intended_url' => $request->fullUrl()]);
                
                // Redirect to time register selection
                return redirect()->route('jcr.link-time-register', $jcr);
            }
        }

        return $next($request);
    }

    protected function shouldSkip(Request $request): bool
    {
        $skipRoutes = [
            'time-registers.*',
            'logout',
            'login',
            'rig-signature.*',
            'ajax.*',
            'jcr.create',
            'jcr.store',
            'jcr.link-time-register.*',
        ];

        foreach ($skipRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }
}