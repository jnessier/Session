<?php

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected Session $session;

    protected function setUp(): void
    {
        Dispatcher::run([
            new SessionMiddleware(),
        ]);

        $_SESSION['_testSessionData'] = [
            'a' => 'A',
        ];

        $flash = new Flash();
        $this->session = new Session($flash, '_testSessionData');
    }

    public function testGet()
    {
        $this->assertSame('A', $this->session->get('a'));
        $this->assertSame('default', $this->session->get('b', 'default'));
    }

    public function testExists()
    {
        $this->assertTrue($this->session->exists('a'));
        $this->assertFalse($this->session->exists('b'));
    }

    public function testSet()
    {
        $this->session->set('b', 'B');

        $this->assertSame('B', $this->session->get('b'));
    }

    public function testDelete()
    {
        $this->session->delete('a');

        $this->assertFalse($this->session->exists('a'));
    }

    public function testGetId()
    {
        $this->assertIsString($this->session->getId());
    }

    public function testGenerateId()
    {
        $oldId = $this->session->getId();
        $this->session->generateId();

        $this->assertNotSame($oldId, $this->session->getId());
    }

    public function testDestroy()
    {
        $this->assertTrue($this->session->destroy());
        $this->assertSame(PHP_SESSION_NONE, $this->session->getStatus());
    }

    public function testInvalidDestroy()
    {
        $this->session->destroy();

        $this->expectException(RuntimeException::class);

        $this->session->destroy();
    }

    public function testGetStatus()
    {
        $this->assertSame(PHP_SESSION_ACTIVE, $this->session->getStatus());
    }

    public function testGetName()
    {
        $this->assertSame('SID', $this->session->getName());
    }

    public function testToArray()
    {
        $this->assertSame([
            'a' => 'A',
        ], $this->session->toArray());
    }

    public function testMerge()
    {
        $this->session->set('c', 'C');

        $this->session->merge([
            'a' => 'SpecialA',
            'b' => 'B',
        ]);

        $this->assertSame([
            'a' => 'SpecialA',
            'c' => 'C',
            'b' => 'B',
        ], $this->session->toArray());
    }

    public function testApply()
    {
        $result = $this->session->apply(function (Session $session, string $value) {
            return $session->get('a').$value;
        }, [
            'B',
        ]);

        $this->assertSame('AB', $result);
    }

    public function testEach()
    {
        $this->session->each(function ($value, $key) {
            $this->assertArrayHasKey($key, $this->session->toArray());
            $this->assertContains($value, $this->session->toArray());
        });
    }

    public function testFlash()
    {
        $this->assertInstanceOf(Flash::class, $this->session->flash());
    }
}
