<?php


if (! function_exists('array_dotize')) {
    function array_dotize($keys)
    {
        return explode('.', $keys);
    }
}
