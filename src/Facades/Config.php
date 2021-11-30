<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

/**
 * @see \VividLamp\PipeSkeleton\Helper\Config
 * @mixin \VividLamp\PipeSkeleton\Helper\Config
 */
class Config extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \VividLamp\PipeSkeleton\Helper\Config::class;
    }
}
