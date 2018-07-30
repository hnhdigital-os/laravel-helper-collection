<?php

namespace HnhDigital\HelperCollection;

class Human
{
    /**
     * Consecutive names for byte units.
     *
     * @var array
     */
    public $units_of_bytes = [
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
     * @param int $bytes
     * @param int $decimals
     * @param int $mod
     *
     * @return string
     */
    public function bytes($bytes, $decimals = 2, $mod = 1024)
    {
        $factor = floor((strlen($bytes) - 1) / 3);
        $byte_name = $this->units_of_bytes[$factor];
        if ($mod == 1000) {
            $byte_name = str_replace('B', 'bit', $byte_name);
        }

        return sprintf("%.{$decimals}f %s", $bytes / pow($mod, $factor), $byte_name);
    }

    /**
     * Convert an integer timeset to pretty.
     *
     * @param int $offset
     *
     * @return string
     */
    public function timeOffset($offset)
    {
        $offset_whole = round($offset);
        $offset_decimal = abs($offset - $offset_whole) * 60;

        return (substr($offset, 0, 1) != '-' ? '+' : '').$offset_whole.':'.str_pad($offset_decimal, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Convert seconds to words.
     *
     * @param int   $seconds
     * @param array $options
     *
     * @return string
     */
    public function seconds($seconds, $options = [])
    {
        // Seconds.
        if ($seconds < 60) {
            $time = $seconds;
            $name = 'second';
        }

        // Minutes.
        if ($seconds >= 60) {
            $time = round($seconds / 60, 0);
            $name = 'minute';
        }

        // Hours.
        if ($time >= 60) {
            $time = round($time / 60, 0);
            $name = 'hour';
        }

        // Days.
        if ($time >= 24) {
            $time = round($time / 24, 0);
            $name = 'day';
        }

        if (array_has($options, 'abbrev')) {
            switch ($name) {
                case 'second':
                    $name = 'sec';
                    break;
                case 'minute':
                    $name = 'min';
                    break;
            }
        }

        if (array_has($options, 'single')) {
            $name = substr($name, 0, 1);
        }

        if ($time == 1) {
            $time = '';
            $period = $name;
        } else {
            $period = str_plural($name, $time);
        }

        return sprintf('%s %s', $time, $period);
    }

    /**
     * Truncate a string.
     *
     * @param string $long_text
     * @param int    $length
     * @param array  $options
     *
     * @return string
     */
    public function truncate($long_text, $length, $options = [])
    {
        $short_text = mb_substr($long_text, 0, $length);

        if ($short_text === $long_text) {
            return $long_text;
        }

        $short_text .= '...';

        if (array_get($options, 'html', false)) {
            $short_text .= ' <span class="f-10 f-w-100">(truncated)</span>';
        }

        if (array_get($options, 'html', false)) {
            $short_text = sprintf('<span title="%s">%s</span>', htmlspecialchars($long_text), $short_text);
        }

        return $short_text;
    }

    /**
     * File upload error codes for humans.
     *
     * @param int $code
     *
     * @return string
     */
    public static function getFileUploadErrorMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the maximum file size';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
               return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
                return 'Server side error occurred';
        }

        return 'Unknown upload error';
    }
}
