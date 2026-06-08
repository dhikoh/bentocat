<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Aksi tidak diizinkan. Peran Anda (' . ucfirst($user->role) . ') tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
