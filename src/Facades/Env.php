<?php

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

class Env extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \VividLamp\PipeSkeleton\Helper\Env::class;
    }
}
