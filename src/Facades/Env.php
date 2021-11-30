<?php

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

/**
 * @see \VividLamp\PipeSkeleton\Helper\Env
 * @mixin \VividLamp\PipeSkeleton\Helper\Env
 */
class Env extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \VividLamp\PipeSkeleton\Helper\Env::class;
    }
}
