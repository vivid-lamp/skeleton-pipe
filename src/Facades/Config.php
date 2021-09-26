<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

class Config extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \VividLamp\PipeSkeleton\Helper\Config::class;
    }
}
