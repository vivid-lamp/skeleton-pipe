<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Facades;

use VividLamp\PipeSkeleton\Helper\Facade;

/**
 * @see \VividLamp\PipeSkeleton\Helper\ApiResponse
 * @mixin \VividLamp\PipeSkeleton\Helper\ApiResponse
 */
class ApiResponse extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \VividLamp\PipeSkeleton\Helper\ApiResponse::class;
    }
}
