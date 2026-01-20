<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type === 'vendor') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}