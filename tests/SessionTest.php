<?php

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    /**
     * @var SessionInterface
     */
    protected $session;

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

    public function testGet(): void
    {
        $this->assertSame('A', $this->session->get('a'));
        $this->assertSame('default', $this->session->get('b', 'default'));
    }

    public function testExists(): void
    {
        $this->assertTrue($this->session->exists('a'));
        $this->assertFalse($this->session->exists('b'));
    }

    public function testSet(): void
    {
        $this->session->set('b', 'B');

        $this->assertSame('B', $this->session->get('b'));
    }

    public function testDelete(): void
    {
        $this->session->delete('a');

        $this->assertFalse($this->session->exists('a'));
    }

    public function testGetId(): void
    {
        $this->assertIsString($this->session->getId());
    }

    public function testGenerateId(): void
    {
        $oldId = $this->session->getId();
        $this->session->generateId();

        $this->assertNotSame($oldId, $this->session->getId());
    }

    public function testDestroy(): void
    {
        $this->assertTrue($this->session->destroy());
        $this->assertSame(PHP_SESSION_NONE, $this->session->getStatus());
    }

    public function testInvalidDestroy(): void
    {
        $this->session->destroy();

        $this->expectException(RuntimeException::class);

        $this->session->destroy();
    }

    public function testGetStatus(): void
    {
        $this->assertSame(PHP_SESSION_ACTIVE, $this->session->getStatus());
    }

    public function testGetName(): void
    {
        $this->assertSame('SID', $this->session->getName());
    }

    public function testToArray(): void
    {
        $this->assertSame([
            'a' => 'A',
        ], $this->session->toArray());
    }

    public function testMergeRecursive(): void
    {
        $this->session->set('b', [
            'c' => 'C',
            'd' => 'D'
        ]);

        $this->session->merge([
            'a' => 'SpecialA',
            'b' => [
                'd' => 'SpecialD',
            ]
        ]);

        $this->assertSame([
            'a' => 'SpecialA',
            'b' => [
                'c' => 'C',
                'd' => 'SpecialD'
            ],
        ], $this->session->toArray());
    }

    public function testMerge(): void
    {
        $this->session->set('b', [
            'c' => 'C',
            'd' => 'D'
        ]);

        $this->session->merge([
            'a' => 'SpecialA',
            'b' => [
                'd' => 'SpecialD',
            ]
        ], false);

        $this->assertSame([
            'a' => 'SpecialA',
            'b' => [
                'd' => 'SpecialD',
            ]
        ], $this->session->toArray());
    }

    public function testApply(): void
    {
        $result = $this->session->apply(function (Session $session, string $value) {
            return $session->get('a').$value;
        }, [
            'B',
        ]);

        $this->assertSame('AB', $result);
    }

    public function testEach(): void
    {
        $this->session->each(function ($value, $key) {
            $this->assertArrayHasKey($key, $this->session->toArray());
            $this->assertContains($value, $this->session->toArray());
        });
    }

    public function testFlash(): void
    {
        $this->assertInstanceOf(Flash::class, $this->session->flash());
    }
}
