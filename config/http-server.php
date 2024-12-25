<?php

declare(strict_types=1);

return static function (\VividLamp\PipeSkeleton\Helper\Env $env) {

    $defaultDriver = match (php_sapi_name()) {
        'cli' => 'react',
        'cli-server' => 'fpm'
    };

    return [
        'driver' => $env->get('http-server', $defaultDriver),
        'fpm'    => [],
        'react'  => [
            'host' => '0.0.0.0',
            'port' => 8080,
        ],
    ];
};
