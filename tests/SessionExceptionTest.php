<?php

namespace Neoflow\Session\Test;

use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

class SessionExceptionTest extends TestCase
{
    /**
     * @var SessionInterface
     */
    protected $session;

    protected function setUp(): void
    {
        $this->session = new Session();
    }

    public function testDestroyInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Cannot destroy the session, when the session has not started yet.');

        $this->session->destroy();
    }

    public function testGenerateIdInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Cannot generate the session id, when the session has not started yet.');

        $this->session->generateId();
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetCookieInvalid(): void
    {
        $this->session->start();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Cannot set the cookie options, when the session has already started.');

        $this->session->setCookie([
            'lifetime' => 1440
        ]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetNameInvalid(): void
    {
        $this->session->start();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Cannot set the session name, when the session has already started.');

        $this->session->setName('foo bar');
    }

    /**
     * @runInSeparateProcess
     */
    public function testStartInvalid(): void
    {
        $this->session->start();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Cannot start the session, when the session already has started.');

        $this->session->start();
    }
}
