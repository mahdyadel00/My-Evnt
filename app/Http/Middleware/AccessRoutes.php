<?php

namespace App\Http\Middleware;

use App\Http\Resources\Api\v1\ErrorResource;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccessRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @param null $type
     * @return ErrorResource
     */
    public function handle(Request $request, Closure $next, $type = null)
    {
        return (auth()->user() ?? $request->user())?->user_type !== $type
            && (auth()->user() ?? $request->user())?->user_type !== 'admin'
            ? ErrorResource::make(__('admin.not_allowed'), 403)
            : $next($request);
    }
}
