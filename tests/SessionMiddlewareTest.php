<?php

namespace Neoflow\Session\Test;

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddlewareTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessionMiddlware(): void
    {
        $session = new Session();

        Dispatcher::run([
            new SessionMiddleware($session),
        ]);

        $_SESSION['a'] = 'A';
        $session->setValue('b', 'B');

        $this->assertTrue($session->isStarted());
        $this->assertSame($_SESSION, $session->getValues());
    }
}
