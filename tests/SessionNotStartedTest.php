<?php

use Neoflow\Session\Flash;
use Neoflow\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionNotStartedTest extends TestCase
{
    public function testFlash(): void
    {
        $this->expectException(RuntimeException::class);

        new Flash();
    }

    public function testSession(): void
    {
        $this->expectException(RuntimeException::class);

        session_start();
        $flash = new Flash();
        session_destroy();
        new Session($flash);
    }
}
