<?php

use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddlewareTest extends TestCase
{
    public function testSessionMiddlware()
    {
        Dispatcher::run([
            new SessionMiddleware(),
        ]);

        $this->assertIsArray($_SESSION);
        $this->assertIsString(session_id());
    }

    public function testSessionMiddlewareWithAttribute()
    {
        Dispatcher::run([
            new SessionMiddleware([
                'withAttribute' => true,
            ]),
            function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                $this->assertInstanceOf(Session::class, $request->getAttribute('session'));

                $handler->handle($request);
            },
        ]);


    }
}
