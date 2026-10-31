<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Support multiple roles separated by pipe or comma or arguments
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $subRole) {
                foreach (explode('|', $subRole) as $item) {
                    $item = trim($item);
                    if ($item !== '') {
                        $allowedRoles[] = $item;
                    }
                }
            }
        }

        if (!empty($allowedRoles) && !in_array($user->role, $allowedRoles, true)) {
            // For API or JSON requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak. Peran Anda tidak memiliki izin untuk sumber daya ini.',
                ], 403);
            }

            // Redirect user to their own dashboard with warning
            $redirectRoute = match ($user->role) {
                'admin' => 'admin.dashboard',
                'laboran' => 'laboran.dashboard',
                default => 'peminjam.dashboard',
            };

            return redirect()->route($redirectRoute)
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
