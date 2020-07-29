<?php

namespace Neoflow\Session\Test;

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionExceptionTest extends TestCase
{
    public function testSessionMiddleware(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session already started.');

        session_start();

        Dispatcher::run([
            new SessionMiddleware(),
        ]);
    }

    public function testFlash(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session not started yet.');

        new Flash();
    }

    public function testSession(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session not started yet.');

        session_start();
        $flash = new Flash();
        session_destroy();

        new Session($flash);
    }

    public function testFlashPushNew(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Key "a" does not contain an indexed array to push value.');

        session_start();
        $flash = new Flash();

        $flash->pushNew('a', 'A');
    }

    public function testSessionPushNew(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Key "a" does not contain an indexed array to push value.');

        session_start();
        $flash = new Flash();
        $session = new Session($flash);

        $session->push('a', 'A');
    }
}
