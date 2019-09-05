<?php

namespace HnhDigital\HelperCollection;

use Illuminate\Support\Arr;

class Aws
{
    /**
     * Client connection details.
     *
     * @return array
     */
    public function client($override = [])
    {
        return [
            'version'     => Arr::get($override, 'version', config('hnhdigital.aws.version')),
            'region'      => Arr::get($override, 'region', config('hnhdigital.aws.region')),
            'credentials' => [
                'key'    => Arr::get($override, 'key', config('hnhdigital.aws.key')),
                'secret' => Arr::get($override, 'secret', config('hnhdigital.aws.secret')),
            ],
        ];
    }
}
