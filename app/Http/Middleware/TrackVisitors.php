<?php

namespace App\Http\Middleware;

use App\Models\VisitorTracking;
use Closure;
use Illuminate\Http\Request;

class TrackVisitors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only track GET requests and non-admin routes
        if ($request->isMethod('get') && !$request->is('admin/*')) {
            try {
                VisitorTracking::recordVisit($request, $request->path());
            } catch (\Exception $e) {
                \Log::error('Visitor tracking failed: ' . $e->getMessage());
            }
        }
        
        return $response;
    }
}
