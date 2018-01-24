<?php

namespace HnhDigital\HelperCollection;

use DateTimeZone;
use Carbon\Carbon;

class Timezone
{
    /**
     * Region name and mask.
     *
     * @var array
     */
    private static $regions = [
        'Africa'     => DateTimeZone::AFRICA,
        'America'    => DateTimeZone::AMERICA,
        'Arctic'     => DateTimeZone::ARCTIC,
        'Antarctica' => DateTimeZone::ANTARCTICA,
        'Asia'       => DateTimeZone::ASIA,
        'Atlantic'   => DateTimeZone::ATLANTIC,
        'Australia'  => DateTimeZone::AUSTRALIA,
        'Europe'     => DateTimeZone::EUROPE,
        'Indian'     => DateTimeZone::INDIAN,
        'Pacific'    => DateTimeZone::PACIFIC,
    ];

    /**
     * Returns a region grouped array.
     *
     * @param array $config
     *
     * @return array
     */
    public static function data($config = [])
    {
        foreach (self::$regions as $region => $mask) {
            $zones = DateTimeZone::listIdentifiers($mask);

            foreach ($zones as $timezone) {
                
                // Time at this timezone.
                $time = Carbon::now($timezone);

                // Convert timezone to human readable.
                $offset = '('.app('Human')->timeOffset($time->offsetHours).')';

                // Default name.
                $name = $timezone;

                // Remove the region from the name.
                if (array_get($config, 'remove-region', false)) {
                    $name = substr($timezone, strlen($region) + 1);
                }

                // Remove the offset.
                if (array_get($config, 'remove-offset', false)) {
                    $offset = '';
                }

                // Show the time.
                $current_time = '';
                if (array_get($config, 'show-time', false)) {
                    $current_time = $time->format(array_get($config, 'show-time-format', 'g:ia'));
                }

                // Place entry.
                array_set($timezones, strtoupper($region).'.'.$timezone, sprintf('%s %s %s', 
                    $name,
                    $offset,
                    $current_time
                ));
            }
        }

        return $timezones;
    }

    /**
     * Return a flat array.
     *
     * @param array $config
     *
     * @return array
     */
    public static function optionsArray($config = [])
    {
        $data = self::data($config);

        $result = [];

        foreach ($data as $name => $zones) {
            if (array_get($config, 'include-region', false)) {
                $result[] = [array_get($config, 'region-value', null), $name];
            }
            foreach ($zones as $timezone => $zone) {
                $result[] = [$timezone, $zone];
            }
        }

        return $result;
    }
}
