<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($request->is('menu') || $request->is('menu/*')) {
            return $next($request);
        }
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $segment = $request->segment(1) ?? '/';
        $segment = $segment === '' ? '/' : $segment;

        $whitelist = ['generator'];
        if (in_array($segment, $whitelist, true)) {
            return $next($request);
        }

        $hasAccess = Menu::query()
            ->where('url', $segment)
            ->whereHas('roles', function ($query) use ($user) {
                $query->where('role_id', $user->role_id);
            })
            ->exists();

        if ($hasAccess) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
