<?php

namespace HnhDigital\HelperCollection;

class Color
{
    /**
     * Contrast the given hex based color.
     *
     * @return array
     */
    public function contrastFromHex($hex)
    {
        $hash = '';

        if (substr($hex, 0, 1) === '#') {
            $hash = '#';
            $hex = substr($hex, 1);
        }

        $color = $this->contrastFromRGB(...sscanf($hex, '%02x%02x%02x'));

        return sprintf('%s%02x%02x%02x', $hash, $color['r'], $color['g'], $color['b']);
    }

    /**
     * Contrast the given RGB based color.
     *
     * @param  string  $r
     * @param  string  $g
     * @param  string  $b
     * @return array
     */
    public function contrastFromRGB($r, $g, $b)
    {
        return [
            'r' => ($r < 128) ? 255 : 0,
            'g' => ($g < 128) ? 255 : 0,
            'b' => ($b < 128) ? 255 : 0,
        ];
    }
}
