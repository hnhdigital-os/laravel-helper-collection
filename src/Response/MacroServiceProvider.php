<?php

namespace HnhDigital\HelperCollection\Response;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        // Providesd the ability to download a file from a given path.
        // Will also return a not modified header if file has not changed.
        Response::macro('downloadAndCache', function ($file_path) {
            if (! empty(request()->header('If-Modified-Since'))) {
                $cache_last_modified = strtotime(request()->header('If-Modified-Since'));
                $file_last_modified = filemtime($file_path);
                if ($file_last_modified == $cache_last_modified) {
                    header('HTTP/1.1 304 Not Modified');
                    exit();
                }
            }

            return Response::file($file_path);
        });
    }
}
