<?php

namespace HnhDigital\HelperCollection\Middleware;

use Closure;
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
                echo array_get($_SERVER, 'HTTP_REFERER', array_get($_SERVER, 'HTTP_ORIGIN', secure_url('')));
                exit();
            }

            return $next($request);
        }
    }
}
