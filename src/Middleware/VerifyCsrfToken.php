<?php

namespace HnhDigital\HelperCollection\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as ParentVerifyCsrfToken;

class VerifyCsrfToken extends ParentVerifyCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Exception $exception) {
            if ($request->ajax()) {
                header('X-FORCE_FRONTEND_REDIRECT: 1');
                echo Arr::get($_SERVER, 'HTTP_REFERER', Arr::get($_SERVER, 'HTTP_ORIGIN', secure_url('')));
                exit();
            }

            return $next($request);
        }
    }
}
