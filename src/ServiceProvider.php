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
        if ($this->app['config']->get('hnhdigital.blade.enabled')) {
            $this->registerBladeDirectives();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/regex.php', 'hnhdigital.regex');

        $this->app->singleton('Aws', function () {
            return new Aws();
        });

        $this->app->singleton('Color', function () {
            return new Color();
        });

        $this->app->singleton('DateHelper', function () {
            return new DateHelper();
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
        return ['Aws', 'Color', 'Database', 'DateHelper', 'FileSystem', 'Human', 'LaravelModel', 'SemVer', 'Timezone'];
    }

    /**
     * Register blade directives.
     *
     * @return void
     */
    private function registerBladeDirectives()
    {
        // Start capturing.
        if (! $this->app['config']->has('hnhdigital.blade.disable.capture')) {
            blade::directive('capture', function () {
                return '<?php ob_start(); ?>';
            });

            // Stop capturing.
            blade::directive('endcapture', function ($name) {
                if (substr($name, 0, 1) == '$') {
                    $name = substr($text, 1);
                }
                $name = trim($name, "'\"");

                return '<?php $'.$name.' = ob_get_clean(); ?>';
            });
        }

        // Call.
        if (! $this->app['config']->has('hnhdigital.blade.disable.call')) {
            blade::directive('call', function ($call) {
                $name = trim($name, "'\"");

                return "<?php $call; ?>";
            });
        }

        // CSRF.
        // @todo Remove when dropping L5.5 support.
        if (! $this->app['config']->has('hnhdigital.blade.disable.csrf')) {
            blade::directive('csrf', function () {
                return '<?= csrf_field(); ?>';
            });
        }

        // Single line of php
        if (! $this->app['config']->has('hnhdigital.blade.disable.raw')) {
            blade::directive('raw', function ($raw) {
                return "<?php $raw; ?>";
            });
        }

        // Multiple lines of php.
        if (! $this->app['config']->has('hnhdigital.blade.disable.php')) {
            blade::directive('php', function ($raw) {
                return "<?php\n$raw\n?>";
            });
        }

        // Share with view.
        if (! $this->app['config']->has('hnhdigital.blade.disable.share')) {
            blade::directive('share', function ($share) {
                return "<?php view()->share($share); ?>";
            });
        }

        // Use.
        if (! $this->app['config']->has('hnhdigital.blade.disable.use')) {
            blade::directive('use', function ($use) {
                return "<?php use $use; ?>";
            });
        }

        // Route.
        blade::directive('route', function ($use) {
            return "<?= route($use) ?>";
        });

        if (! $this->app['config']->has('hnhdigital.blade.disable.locale')) {
            // Format number.
            blade::directive('locale_currency_symbol', function () {
                return '<?= locale_currency_symbol() ?>';
            });

            // Format number.
            blade::directive('locale_format_number', function ($args) {
                return "<?= locale_format_number($args) ?>";
            });

            // Format currency.
            blade::directive('float_format_currency', function ($args) {
                return "<?= float_format_currency($args) ?>";
            });
        }

        if (! $this->app['config']->has('hnhdigital.blade.disable.datetime')) {
            // Format date using user format and timezone.
            blade::directive('user_timedate', function ($args) {
                return "<?= user_timedate($args) ?>";
            });

            // Format date using user format and timezone.
            blade::directive('user_time', function ($args) {
                return "<?= user_time($args) ?>";
            });

            // Format date using user format and timezone.
            blade::directive('user_date', function ($args) {
                return "<?= user_date($args) ?>";
            });
        }

        // Various text helper directives.
        if (! $this->app['config']->has('hnhdigital.blade.disable.text')) {
            foreach (['__', 'camel_case', 'kebab_case', 'snake_case', 'studly_case', 'str_plural', 'str_tense', 'title_case'] as $function_name) {
                blade::directive($function_name, function ($text) use ($function_name) {
                    $text = implode(',', array_map(function ($value) {
                        $value = trim($value, "'\" ");
                        if (substr($value, 0, 1) !== '$') {
                            $value = "'$value'";
                        }

                        return $value;
                    }, explode(',', $text)));

                    if (stripos($function_name, '_case') !== false) {
                        $function_name = str_replace('_case', '', $function_name);
                        $function_name = 'Str::'.$function_name;
                    }

                    if (stripos($function_name, 'str_') !== false) {
                        $function_name = str_replace('str_', '', $function_name);
                        $function_name = 'Str::'.$function_name;
                    }

                    return "<?= $function_name($text); ?>";
                });
            }

            // @str_upper => strtoupper
            blade::directive('str_upper', function ($text) use ($function_name) {
                if (substr($text, 0, 1) !== '$') {
                    $text = "'$text'";
                }

                return "<?= strtoupper($text); ?>";
            });

            // @str_lower => strtolower
            blade::directive('str_lower', function ($text) use ($function_name) {
                if (substr($text, 0, 1) !== '$') {
                    $text = "'$text'";
                }

                return "<?= strtolower($text); ?>";
            });
        }
    }
}
