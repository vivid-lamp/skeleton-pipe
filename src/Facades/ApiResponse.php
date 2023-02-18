<?php

declare(strict_types=1);

namespace Acme\Facades;

use Acme\Helper\Facade;

/**
 * @see \Acme\Helper\ApiResponse
 * @mixin \Acme\Helper\ApiResponse
 */
class ApiResponse extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \Acme\Helper\ApiResponse::class;
    }
}
