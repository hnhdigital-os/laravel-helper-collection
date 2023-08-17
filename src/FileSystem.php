<?php

namespace HnhDigital\HelperCollection;

class FileSystem
{
    /**
     * Enclose the provided string with slashes.
     *
     * @param  string  $path
     * @return string
     */
    public function enclosePath($path)
    {
        // Add starting slash.
        if (substr($path, 0, 1) !== '/') {
            $path = '/'.$path;
        }

        // Add trailing slash.
        if (substr($path, -1, 1) !== '/') {
            $path .= '/';
        }

        return $path;
    }
}
