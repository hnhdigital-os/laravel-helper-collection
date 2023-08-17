<?php

namespace HnhDigital\HelperCollection\Middleware;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (config('app.force_https', false)) {
            $request->server->set('HTTPS', true);
            app('url')->forceScheme('https');
        }

        return $next($request);
    }
}
