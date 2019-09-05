<?php

namespace HnhDigital\HelperCollection\Middleware;

class XhrRequestOnly
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $request->ajax() && ! $request->wantsJson()) {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
