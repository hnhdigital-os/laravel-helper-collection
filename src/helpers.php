<?php

use Symfony\Component\Intl\Intl;

/*
 * Converts a list of items into a dotted representation.
 */
if (!function_exists('array_dotize')) {
    function array_dotize(...$keys)
    {
        return implode('.', $keys);
    }
}

/*
 * Converts a string, an array of strings, or a collection into an array of values.
 */
if (!function_exists('array_it')) {
    function array_it($values, $key = false)
    {
        $result = [];

        if (!($values instanceof \Illuminate\Database\Eloquent\Collection)) {
            $values = is_array($values) ? $values : [$values];
        }

        foreach ($values as $value) {
            // Check given values. If any have eloquent models, convert to their key value.
            if (!empty($key) && is_object($value) && $value instanceof \Illuminate\Database\Eloquent\Model) {
                $result[] = $key === true ? $value->getKey() : $value->$key;
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }
}

/*
 * Get's current user.
 */
if (!function_exists('user')) {
    function user($guard = null)
    {
        if (auth($guard)->check()) {
            return auth($guard)->user();
        }
    }
}

/*
 * Get's current user.
 */
if (!function_exists('user_id')) {
    function user_id($guard = null)
    {
        if (auth($guard)->check()) {
            return auth($guard)->user()->getKey();
        }
    }
}

/*
 * Converts a number to locale.
 */
if (!function_exists('user_locale')) {
    function user_locale($guard = null)
    {
        if (auth($guard)->check() && !empty(user($guard)->country_code)) {
            return user($guard)->country_code;
        }

        return app()->getLocale();
    }
}

if (!function_exists('locale_format_number')) {
    function locale_format_number($money, $config = [])
    {
        $money->setLocale(user_locale());

        return $money->format();
    }
}

if (!function_exists('locale_currency_symbol')) {
    function locale_currency_symbol($currency = 'USD')
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol($currency);
    }
}

if (!function_exists('float_format_currency')) {
    function float_format_currency($value)
    {
        $value = preg_replace("/([-]?)(.*?)\xC2\xA0([0-9,.]*)/", '<span class="f-left">$2</span> $1$3', $value, -1, $count);
        $value = preg_replace('/([-]?)(.*?)([0-9,.]+)/', '<span class="f-left">$2</span> $1$3', $value, -1, $count);

        if ($count == 0) {
            $value = preg_replace("/([-]?)([0-9,.]*)\xC2\xA0(.*?)/", '<span class="f-left">$2</span> $1$3', $value, -1, $count);
        }

        return $value;
    }
}

/*
 * Converts a DateTime to user's timezone.
 */
if (!function_exists('user_tz')) {
    function user_tz()
    {
        return Auth()->user()->timezone;
    }
}

/*
 * Converts a DateTime to user's timezone.
 */
if (!function_exists('user_timezone')) {
    function user_timezone($datetime = null)
    {
        if (is_null($datetime)) {
            $datetime = Carbon\Carbon::now('UTC');
        }

        if (auth()->check()) {
            return Auth()->user()->timezone($datetime);
        }

        return $datetime->timezone('GMT');
    }
}

/*
 * Converts a DateTime to user's timezone and specified time/date format.
 */
if (!function_exists('user_timedate')) {
    function user_timedate($datetime, $timezone = true)
    {
        if (auth($auth()->guard())->check()) {
            $format = Auth()->user()->time_date_format;
        } else {
            $format = 'Y-m-d H:i';
        }

        $format .= timezone_format($timezone);

        return user_timezone($datetime)->format($format);
    }
}

/*
 * Converts a DateTime to user's timezone and specified time format.
 */
if (!function_exists('user_time')) {
    function user_time($datetime, $timezone = true)
    {
        if (auth()->check()) {
            $format = Auth()->user()->time_format;
        } else {
            $format = 'H:i';
        }
        $format .= timezone_format($timezone);
        return user_timezone($datetime)->format($format);
    }
}

/*
 * Converts a DateTime to user's timezone and specified date format.
 */
if (!function_exists('user_date')) {
    function user_date($datetime, $timezone = true)
    {
        if (auth()->check()) {
            $format = Auth()->user()->date_format;
        } else {
            $format = 'Y-m-d';
        }
        $format .= timezone_format($timezone);
        return user_timezone($datetime)->format($format);
    }
}

/*
 * Converts a DateTime to user's timezone and specified date format.
 */
if (!function_exists('timezone_format')) {
    function timezone_format($return)
    {
        return $return ? ' T' : '';
    }
}

/*
 * Converts word to correct tense.
 */
if (!function_exists('str_tense')) {
    function str_tense($word, $count)
    {
        if (in_array($word, ['is', 'are'])) {
            if ($count == 0 || $count > 1) {
                return 'are';
            }

            return 'is';
        }

        return $word;
    }
}
