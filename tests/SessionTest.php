<?php

namespace Neoflow\Session\Test;

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SessionTest extends TestCase
{
    /**
     * @var SessionInterface
     */
    protected $session;

    protected function setUp(): void
    {
        $this->session = new Session([
            'iniSettings' => [
                'gc_maxlifetime' => 1440
            ]
        ]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroy(): void
    {
        $this->session->start();

        $this->assertTrue($this->session->destroy());
        $this->assertSame(PHP_SESSION_NONE, $this->session->getStatus());
    }

    public function testDestroyInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Destroy session failed. Session not started yet.');

        $this->session->destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGenerateId(): void
    {
        $this->session->start();

        $oldId = $this->session->getId();
        $this->session->generateId();

        $this->assertNotSame($oldId, $this->session->getId());
    }

    public function testGenerateIdInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Generate session id failed. Session not started yet.');

        $this->session->generateId();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetCookie(): void
    {
        $this->session->start();

        $this->assertIsArray($this->session->getCookie());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetId(): void
    {
        $this->session->start();

        $this->assertIsString($this->session->getId());
    }

    public function testGetIdInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session id does not exists. Session not started yet.');

        $this->session->getId();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetName(): void
    {
        $this->session->start();

        $this->assertSame('sid', $this->session->getName());
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
    public function testGetStatus(): void
    {
        $this->session->start();

        $this->assertSame(PHP_SESSION_ACTIVE, $this->session->getStatus());
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
