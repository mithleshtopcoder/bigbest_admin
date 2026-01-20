<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Routes that should be excluded from logging
     */
    protected $except = [
        'login',
        'logout',
        'api/*',
        'livewire/*',
        '_debugbar/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and exclude certain routes
        if (auth()->check() && $this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Determine if the request should be logged
     */
    protected function shouldLog(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        // Only log GET requests for viewing, POST/PUT/DELETE are handled in controllers
        return $request->isMethod('GET') && !$request->ajax() && !$request->wantsJson();
    }

    /**
     * Log the activity
     */
    protected function logActivity(Request $request, Response $response): void
    {
        // Only log successful responses (2xx status codes)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $route = $request->route();
            $module = $this->extractModule($route);
            
            if ($module) {
                ActivityLogService::logView($module, "Viewed {$module} page");
            }
        }
    }

    /**
     * Extract module name from route
     */
    protected function extractModule($route): ?string
    {
        if (!$route) {
            return null;
        }

        $name = $route->getName();
        $uri = $route->uri();

        // Extract module from route name
        if ($name) {
            $parts = explode('.', $name);
            if (count($parts) > 1) {
                $module = ucfirst(str_replace('-', ' ', $parts[0]));
                return $module;
            }
        }

        // Extract module from URI
        $uriParts = explode('/', trim($uri, '/'));
        if (!empty($uriParts[0]) && $uriParts[0] !== 'home') {
            $module = ucfirst(str_replace('-', ' ', $uriParts[0]));
            return $module;
        }

        return null;
    }
}
