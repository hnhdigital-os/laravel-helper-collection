<?php

namespace HnhDigital\HelperCollection;

class Database
{
    private static $static_unit_interval = [
        '1d' => [1, 'DAY'],
        '7d' => [7, 'DAY'],
        '1m' => [1, 'MONTH'],
        '1y' => [1, 'YEAR'],
    ];

    /**
     * Converts a unit/interval string to the correct INTERVAL constant.
     *
     * eg 1y = [1, YEAR]
     *
     * @return array|null
     */
    public function parseUnitInterval($unit_interval)
    {
        if (array_has(self::$static_unit_interval, $unit_interval)) {
            return array_get(self::$static_unit_interval, $unit_interval);
        }

        $unit = preg_replace('/([A-Za-z]*?)/', '', $unit_interval);
        $interval = preg_replace('/([0-9]*?)/', '', $unit_interval);

        $interval = $this->convertInterval($interval);

        if ($unit > 0 && $interval !== false) {
            return [$unit, $interval];
        }

        return [null, null];
    }

    /**
     * Convert the interval string to the mysql constant.
     *
     * @param string $interval
     *
     * @return string
     */
    public function convertInterval($interval)
    {
        switch (strtolower($interval)) {
            case 'y':
            case 'year':
                $interval = 'YEAR';
                break;
            case 'm':
            case 'month':
                $interval = 'MONTH';
                break;
            case 'd':
            case 'day':
                $interval = 'DAY';
                break;
            case 'h':
            case 'hour':
                $interval = 'HOUR';
                break;
            case 'i':
            case 'min':
            case 'minute':
                $iterval = 'MINUTE';
                break;
            case 's':
            case 'sec':
            case 'second':
                $interval = 'SECOND';
                break;
            default:
                $interval = false;
        }

        return $interval;
    }
}
