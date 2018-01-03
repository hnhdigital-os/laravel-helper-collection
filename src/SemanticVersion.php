<?php

namespace HnhDigital\HelperCollection;

class SemanticVersion
{
    /**
     * Convert a semantic version string to a number.
     *
     * @param string $value
     *
     * @return array
     */
    public static function toNumber($value)
    {
        $version_array = explode('-', $value, 2);
        $version = array_pad(explode('.', $version_array[0]), 4, '00000');
        $version_extra = array_get($version_array, 1, '');

        if (strlen($version[0]) == 8) {
            $version_number = (int)$version[0] * 100 + array_get($version, 1, 0);
        } else {
            $version_number = '';
            foreach ($version as $value) {
                $version_number .= str_pad($value, 5, '0', STR_PAD_LEFT);
            }
            $version_number = (int)$version_number;
        }

        return [$version_number, $version_extra];
    }

}