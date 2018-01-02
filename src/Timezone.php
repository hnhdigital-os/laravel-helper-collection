<?php

namespace HnhDigital\HelperCollection;

use DateTime;
use DateTimeZone;

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
     * @return array
     */
    public static function data()
    {
        $timezones = [];

        foreach (self::$regions as $name => $mask) {
            $zones = DateTimeZone::listIdentifiers($mask);

            foreach ($zones as $timezone) {
                // Lets sample the time there right now
                $time = new DateTime(null, new DateTimeZone($timezone));

                $offset = $time->getOffset() / 60 / 60;
                $offset_whole = round($offset);
                $offset_decimal = abs($offset - $offset_whole) * 60;
                $offset = (substr($offset, 0, 1) != '-' ? '+' : '').$offset_whole.':'.str_pad($offset_decimal, 2, '0', STR_PAD_LEFT);

                // Remove region name and add a sample time
                $timezones[strtoupper($name)][$timezone] = sprintf('%s (%s) %s', strtoupper(substr($timezone, strlen($name) + 1)), $offset, $time->format('g:ia'));
            }
        }

        return $timezones;
    }

    /**
     * Return a flat array.
     *
     * @return array
     */
    public static function optionsArray()
    {
        $data = self::data();
        $result = [];

        foreach ($data as $name => $zones) {
            $result[] = ['BREAK', $name];
            foreach ($zones as $timezone => $zone) {
                $result[] = [$timezone, $zone];
            }
        }

        return $result;
    }
}
