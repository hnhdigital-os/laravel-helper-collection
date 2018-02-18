<?php


if (! function_exists('array_dotize')) {
    function array_dotize(...$keys)
    {
        return implode('.', $keys);
    }
}

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
