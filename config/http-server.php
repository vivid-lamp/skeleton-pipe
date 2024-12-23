<?php

declare(strict_types=1);

return static function (\VividLamp\PipeSkeleton\Helper\Env $env) {
    return [
        'driver' => $env->get('http-server'),
        'fpm'    => [],
        'react'  => [
            'host' => '0.0.0.0',
            'port' => 8080,
        ],
    ];
};
