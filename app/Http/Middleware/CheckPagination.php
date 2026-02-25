<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPagination
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $controller = explode('\\', $request->route()->getAction()['controller']);
        $model      = explode('Controller', end($controller))[0];
        $model      = !class_exists("App\\Models\\$model")
            ? ucfirst(explode(' ', preg_replace('/(?<!^)[A-Z]/', ' $0', $model))[1])
            : $model;
        $model      = str_contains($model, 'Comment') ? 'Comment' : $model;
        $pageSize   = ($request->header('X-Page-Size')) === 'all' && class_exists("App\\Models\\$model")
            ? ("App\\Models\\$model")::count()
            : (!empty($request->header('X-Page-Size'))
                ? $request->header('X-Page-Size')
                : config('app.pagination'));
        config(['app.pagination' => $pageSize]);
        return $next($request);
    }
}
