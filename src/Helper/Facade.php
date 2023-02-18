<?php

declare(strict_types=1);

namespace Acme\Helper;

use Acme\Application;

abstract class Facade
{

	/**
	 * @throws \Psr\Container\ContainerExceptionInterface
	 * @throws \Psr\Container\NotFoundExceptionInterface
	 */
	protected static function createFacade()
    {
        return Application::get()->getContainer()->get(static::getFacadeClass());
    }

    abstract protected static function getFacadeClass(): string;

	/**
	 * @throws \Psr\Container\ContainerExceptionInterface
	 * @throws \Psr\Container\NotFoundExceptionInterface
	 */
	public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }
}
