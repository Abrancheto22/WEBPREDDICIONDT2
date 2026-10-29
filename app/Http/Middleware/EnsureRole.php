<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roleIds): Response
    {
        $user = $request->user();

        abort_unless(
            $user && in_array((string) $user->idrol, $roleIds, true),
            403,
            'No tiene permisos para realizar esta operación.'
        );

        return $next($request);
    }
}
