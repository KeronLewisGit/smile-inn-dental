<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    public function handle(Request $request, Closure $next, string $role = 'staff'): Response
    {
        $user = $request->user();
        abort_unless($user && in_array($user->role, $role === 'admin' ? ['admin'] : ['admin', 'staff']), 403);

        return $next($request);
    }
}
