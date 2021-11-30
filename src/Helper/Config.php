<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Helper;

/**
 * @author zhanglihui
 */
class Config
{
    /** @var string */
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function get($name, $default = null)
    {
        $nameSeparate = explode('.', $name);
        $configPath = $this->path . current($nameSeparate) . '.php';

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
