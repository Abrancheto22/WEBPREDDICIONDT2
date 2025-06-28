<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleAssets
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (config('app.env') === 'local') {
            $response->headers->set('X-Assets-Path', config('app.url') . '/public/plantilla/assets/');
        }

        return $response;
    }
}
