<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

/**
 * @see \Acme\App\Helper\Config
 */
class Config extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \Acme\App\Helper\Config::class;
    }
}
