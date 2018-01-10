<?php

namespace HnhDigital\HelperCollection;

/*
 * This file is part of Laravel Navigation Builder.
 *
 * (c) Rocco Howard <rocco@hnh.digital>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * This is the facade class.
 *
 * @author Rocco Howard <rocco@hnh.digital>
 */
class SemanticVersionFacade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SemVer';
    }
}
