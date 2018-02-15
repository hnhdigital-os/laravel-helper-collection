<?php

namespace HnhDigital\HelperCollection;

/*
 * This file is part of Laravel Navigation Builder.
 *
 * (c) Rocco Howard <rocco@hnh.digital>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * This is the service provider class.
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Aws', function () {
            return new Aws();
        });

        $this->app->singleton('Database', function () {
            return new Database();
        });

        $this->app->singleton('FileSystem', function () {
            return new FileSystem();
        });

        $this->app->singleton('Human', function () {
            return new Human();
        });

        $this->app->singleton('LaravelModel', function () {
            return new LaravelModel();
        });

        $this->app->singleton('SemVer', function () {
            return new SemanticVersion();
        });

        $this->app->singleton('Timezone', function () {
            return new Timezone();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Aws', 'Database', 'FileSystem', 'Human', 'LaravelModel', 'SemVer', 'Timezone'];
    }

    /**
     * Register blade directives.
     *
     * @return void
     */
    private function registerBladeDirectives()
    {
        blade::directive('capturestart', function () {
            return '<?php ob_start(); ?>';
        });

        blade::directive('capturestop', function ($name) {
            $name = str_replace('$', '', $name);
            $name = substr($name, 1, -1);

            return '<?php $'.$name.' = ob_get_clean(); ?>';
        });

        blade::directive('call', function ($call) {
            $call = trim($call, "'");

            return "<?php $call; ?>";
        });

        blade::directive('csrf', function () {
            return '<?= csrf_field(); ?>';
        });

        blade::directive('raw', function ($raw) {
            return "<?php $raw; ?>";
        });

        blade::directive('use', function ($use) {
            return "<?php use $use; ?>";
        });

        blade::directive('plural', function ($text) {
            return "<?= str_plural($text); ?>";
        });
    }
}
