<?php

namespace HnhDigital\HelperCollection;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class DateHelper
{
    /**
     * Generate the correct keys for the given type and date range.
     *
     * @param string $type
     * @param string|Carbon|CarbonImmutable $from_date
     * @param null|string|Carbon|CarbonImmutable $to_date
     *
     * @return array
     */
    public static function generateKeys($type, $from_date, $to_date = null)
    {
        if (is_null($to_date)) {
            $to_date = CarbonImmutable::now();
        }

        if (is_string($from_date)) {
            $from_date = Carbon::createFromFormat('Y-m-d', $from_date);
        } elseif ($from_date instanceof CarbonImmutable) {
            $from_date = Carbon::parse($from_date);
        }

        if (is_string($to_date)) {
            $to_date = CarbonImmutable::createFromFormat('Y-m-d', $to_date ?? date('Y-m-d'));
        }

        $result = [];

        switch ($type) {
            // Generate day keys.
            case 'd':
                while ($from_date->isBefore($to_date)) {
                    $result[] = $from_date->format('Y-m-d');
                    $from_date->addDay();
                }
                break;

            // Generate month keys.
            case 'm':
                while ($from_date->isBefore($to_date)) {
                    $result[] = $from_date->format('Y-m');
                    $from_date->endOfMonth()->addDay();
                }
                break;

            // Generate week keys.
            case 'w':
                while ($from_date->isBefore($to_date)) {
                    $result[] = $from_date->isoWeekYear().'-'.$from_date->isoWeek();
                    $from_date->next(Carbon::MONDAY);
                }
                break;

            // Generate year keys.
            case 'y':
                while ($from_date->isBefore($to_date)) {
                    $result[] = $from_date->format('Y');
                    $from_date->addYear();
                }
                break;

            // Generate financial year keys.
            case 'fy':
                $from_fy = $from_date->year;
                if ($from_date->month >= 1) {
                    $from_fy++;
                }

                $to_fy = $to_date->year;
                if ($to_date->month >= 1) {
                    $to_fy++;
                }

                $result = range($from_fy, $to_fy);

                break;
        }

        return $result;
    }
}
