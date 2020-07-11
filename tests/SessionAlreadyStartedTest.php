<?php

use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use Neoflow\Session\Middleware\SessionMiddleware;
use PHPUnit\Framework\TestCase;

class SessionAlreadyStartedTest extends TestCase
{
    protected function setUp(): void
    {
        session_start();
    }

    public function test()
    {
        $this->expectException(RuntimeException::class);

        Dispatcher::run([
            new SessionMiddleware(),
        ]);
    }
}
