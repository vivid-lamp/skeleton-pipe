<?php


namespace Framework\App\Helper;


use Framework\Bootstrap\App;

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