<?php

namespace HnhDigital\HelperCollection;

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
            'version'     => array_get($override, 'version', config('hnhdigital.aws.version')),
            'region'      => array_get($override, 'region', config('hnhdigital.aws.region')),
            'credentials' => [
                'key'    => array_get($override, 'key', config('hnhdigital.aws.key')),
                'secret' => array_get($override, 'secret', config('hnhdigital.aws.secret')),
            ],
        ];
    }
}
