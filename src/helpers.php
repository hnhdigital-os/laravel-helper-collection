<?php

/**
 * Converts a list of items into a dotted representation.
 */
if (! function_exists('array_dotize')) {
    function array_dotize(...$keys)
    {
        return implode('.', $keys);
    }
}

/**
 * Converts a string, an array of strings, or a collection into an array of values.
 */
if (! function_exists('array_it')) {
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

/**
 * Converts a DateTime to user's timezone.
 */
if (! function_exists('user_timezone')) {
    function user_timezone($datetime = null)
    {
        if (is_null($datetime)) {
            $datetime = Carbon\Carbon::now('UTC');
        }

        return Auth()->user()->timezone($datetime);
    }
}

/**
 * Converts a DateTime to user's timezone and specified time/date format.
 */
if (! function_exists('user_timedate')) {
    function user_timedate($datetime)
    {
        return user_timezone($datetime)->format(Auth()->user()->time_date_format);
    }
}

/**
 * Converts a DateTime to user's timezone and specified time format.
 */
if (! function_exists('user_time')) {
    function user_time($datetime)
    {
        return user_timezone($datetime)->format(Auth()->user()->time_format);
    }
}

/**
 * Converts a DateTime to user's timezone and specified date format.
 */
if (! function_exists('user_date')) {
    function user_date($datetime)
    {
        return user_timezone($datetime)->format(Auth()->user()->date_format);
    }
}
