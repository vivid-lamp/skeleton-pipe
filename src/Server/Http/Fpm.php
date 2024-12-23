<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Server\Http;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Server\RequestHandlerInterface;

class Fpm implements ServerInterface
{
    public function __construct(
        protected RequestHandlerInterface $requestHandler,
    ) {
    }

    public function serve(): void
    {
        $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $response = $this->requestHandler->handle($request);

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();
    }
}
