<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackProductView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Track product view if this is a product detail page
        if ($request->route() && $request->route()->parameter('id')) {
            $productId = $request->route()->parameter('id');

            // Use a queued job to avoid slowing down the response
            dispatch(function () use ($productId) {
                \App\Services\ActivityTracker::trackView($productId);
            })->afterResponse();
        }

        return $response;
    }
}
