<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $requiredAccesses
     */
    public function handle(Request $request, Closure $next, string ...$requiredAccesses): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        if (empty($requiredAccesses)) {
            return $next($request);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($requiredAccesses as $requiredAccess) {
            $normalizedAccess = trim((string) $requiredAccess);
            if ($normalizedAccess === '') {
                continue;
            }

            if ($normalizedAccess === User::ROLE_SUPER_ADMIN && $user->isSuperAdmin()) {
                return $next($request);
            }

            if ($user->hasAccess($normalizedAccess)) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard.admin')->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
    }
}
