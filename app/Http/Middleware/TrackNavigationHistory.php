<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackNavigationHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track AJAX requests, assets, or the back route itself
        if ($request->ajax() || 
            $request->routeIs('back') || 
            $request->routeIs('chat.messages') ||
            $request->is('storage/*') || 
            $request->is('uploads/*') ||
            $request->is('build/*')
        ) {
            return $next($request);
        }

        $history = session()->get('navigation_history', []);
        $currentUrl = $request->fullUrl();

        // Get the last URL from history
        $lastUrl = end($history);

        // Only push if different from last URL
        if ($lastUrl !== $currentUrl) {
            // Keep history manageable, say last 10 pages
            if (count($history) >= 10) {
                array_shift($history);
            }
            $history[] = $currentUrl;
            session()->put('navigation_history', $history);
        }

        return $next($request);
    }
}
