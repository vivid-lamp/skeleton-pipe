<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Helper;

use VividLamp\PipeSkeleton\Bootstrap\App;

abstract class Facade
{
    protected static function createFacade()
    {
        return App::getInstance()->getContainer()->get(static::getFacadeClass());
    }

    abstract protected static function getFacadeClass(): string;
    
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }
}
