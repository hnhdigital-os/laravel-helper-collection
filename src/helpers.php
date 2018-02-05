<?php


if (! function_exists('array_dotize')) {
    function array_dotize(...$keys)
    {
        return implode('.', $keys);
    }
}

if (! function_exists('array_it')) {
    function array_it($values, $check_values = false)
    {
        $values = is_array($values) ? $values : [$values];

        // Check given values. If any have eloquent models, convert to their key value.
        if ($check_values) {
            foreach ($values as &$value) {
                if (is_object($value) && $value instanceof \Illuminate\Database\Eloquent\Model) {
                    $value = $value->getKey();
                }
            }
        }

        return $values;
    }
}
