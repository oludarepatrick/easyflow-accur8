<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
