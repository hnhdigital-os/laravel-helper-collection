<?php

namespace HnhDigital\HelperCollection\Middleware;

use URL;

class ForceHttps
{
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
        if (env('APP_FORCE_HTTPS', false)) {
            $request->server->set('HTTPS', true);
            app('url')->forceScheme('https');

            if (class_exists('\\Roumen\\Asset\\Asset', false)) {
                \Roumen\Asset\Asset::$secure = true;
            }
        }

        return $next($request);
    }
}
