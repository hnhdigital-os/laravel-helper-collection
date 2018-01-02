<?php

namespace HnhDigital\HelperCollection;

class Human
{
    /**
     * Consecutive names for byte units.
     *
     * @var array
     */
    public static $bytes_units = [
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB',
        'EB',
        'ZB',
        'YB',
    ];

    /**
     * Convert bytes to a word.
     *
     * @param  integer $bytes
     * @param  integer $decimals
     * @param  integer $mod
     *
     * @return string
     */
    public static function bytes($bytes, $decimals = 2, $mod = 1024)
    {
        $factor = floor((strlen($bytes) - 1) / 3);
        $byte_name = static::$bytes_units[$factor];
        if ($mod == 1000) {
            $byte_name = str_replace('B', 'bit', $byte_name);
        }

        return sprintf("%.{$decimals}f %s", $bytes / pow($mod, $factor), $byte_name);
    }
}
