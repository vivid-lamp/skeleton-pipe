<?php

namespace Acme\Server\Http;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;

class Fpm implements ServerInterface
{
    public function __construct(
        protected ContainerInterface $container
    )
    {
    }

    public function serve(): void
    {
        $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $router  = (require 'config/route.php')($this->container);

        $router->handle($request);

        $response = $router->handle($request);

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();
    }
}