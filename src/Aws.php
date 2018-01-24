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
            'version'     => array_get($override, 'version', env('AWS_VERSION')),
            'region'      => array_get($override, 'region', env('AWS_REGION')),
            'credentials' => [
                'key'    => array_get($override, 'key', env('AWS_ACCESS_KEY_ID')),
                'secret' => array_get($override, 'secret', env('AWS_SECRET_ACCESS_KEY')),
            ],
        ];
    }
}
