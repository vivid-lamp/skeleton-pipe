<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Helper;

class Env
{
    /** @var string */
    protected $path;

    /** @var mixed */
    protected $content;

    /** @var bool */
    protected $parsed;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function parse()
    {
        return $this->parsed
            ? $this->content
            : $this->content = parse_ini_file($this->path, true);
    }

    public function get(string $name, $default = null)
    {
        $content = $this->parse();
    
        if ($content === false) {
            return $default;
        }
        $nameSeparate = explode('.', $name);
        foreach ($nameSeparate as $item) {
            if (!isset($content[$item])) {
                return $default;
            }
            $content = $content[$item];
        }
        return $content;
    }

    public function all()
    {
        return $this->parse();
    }
}
