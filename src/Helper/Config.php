<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Helper;

/**
 * Class Config
 * @author zhanglihui
 */
class Config
{
    private static $configDir = __DIR__ . '/../../Config/';

    public static function get($name, $default = null)
    {
        $nameSeparate = explode('.', $name);
        $configPath = self::$configDir . current($nameSeparate) . '.php';
        if (!file_exists($configPath)) {
            return $default;
        }
        $content = require $configPath;
        while (false !== ($item = next($nameSeparate))) {
            if (!isset($content[$item])) {
                return $default;
            }
            $content = $content[$item];
        }
        return $content;
    }
}
