<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictMarketingRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->role === 'marketing') {
            $route = $request->route();
            $routeName = $route ? $route->getName() : '';
            $method = $request->method();

            // 1. Allowed routes/actions for marketing
            $allowedRoutes = [
                'admin.dashboard',
                'admin.logout',
                // Settings
                'admin.settings.index',
                'admin.settings.update',
                // Marketing Logs (My logs CRUD)
                'admin.my-logs.index',
                'admin.my-logs.create',
                'admin.my-logs.store',
                'admin.my-logs.edit',
                'admin.my-logs.update',
                'admin.my-logs.destroy',
            ];

            // Check if route is an article route (CMS)
            $isArticleRoute = $routeName && str_starts_with($routeName, 'admin.articles.');

            if (in_array($routeName, $allowedRoutes) || $isArticleRoute) {
                return $next($request);
            }

            // 2. Block Export CSV routes (e.g. outlets.export, customers.export, leads.export)
            if ($routeName && (str_ends_with($routeName, '.export') || str_contains($routeName, 'export'))) {
                abort(403, 'Aksi tidak diizinkan. Peran Marketing tidak diperbolehkan mengekspor data.');
            }

            $uri = $request->getRequestUri();
            if (str_contains($uri, '/export')) {
                abort(403, 'Aksi tidak diizinkan. Peran Marketing tidak diperbolehkan mengekspor data.');
            }

            // 3. Block Write operations (POST, PUT, PATCH, DELETE)
            if ($method !== 'GET') {
                abort(403, 'Aksi tidak diizinkan. Akun Anda memiliki akses Baca-Saja (Read-Only).');
            }

            // 4. Block Create and Edit views for read-only entities
            if (str_contains($uri, '/create') || str_contains($uri, '/edit')) {
                abort(403, 'Halaman tidak diizinkan. Akun Anda memiliki akses Baca-Saja (Read-Only).');
            }
        }

        return $next($request);
    }
}
