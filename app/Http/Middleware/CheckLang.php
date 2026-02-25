<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLang
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\JsonResponse|Illumin`ate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        config([
            'app.locale'   => empty($request->header('x-Locale')) ? config('app.locale') : $request->header('x-Locale'),
            'app.timezone' => empty($request->header('X-Time-Zone')) ? config('app.timezone') : $request->header('X-Time-Zone'),
        ]);
        return $next($request);
    }
}
