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
        $this->expectExceptionMessage('Destroy session failed. Session not started yet.');

        $this->session->destroy();
    }

    public function testGenerateIdInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Generate session id failed. Session not started yet.');

        $this->session->generateId();
    }


    public function testGetIdInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session id does not exists. Session not started yet.');

        $this->session->getId();
    }


    public function testGetNameInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session name does not exists. Session not started yet.');

        $this->session->getName();
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetNameInvalid(): void
    {
        $this->session->start();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Set session name failed. Session already started.');

        $this->session->setName('foo bar');
    }

    /**
     * @runInSeparateProcess
     */
    public function testStartInvalid(): void
    {
        $this->session->start();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session start failed. Session already started.');

        $this->session->start();
    }
}
