<?php

namespace Neoflow\Session\Test;

use Middlewares\Utils\Dispatcher;
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
        Dispatcher::run([
            new SessionMiddleware(),
        ]);

        $_SESSION['_testSessionData'] = [
            'a' => 'A',
            'b' => [
                'b-a' => 'b-A',
                'b-b' => 'b-B',
            ],
            'c' => [
                'c-A',
                'c-B',
            ],
            'd' => null
        ];

        $this->session = new Session();
    }

    public function testGet(): void
    {
        $this->assertSame('A', $this->session->get('a'));
        $this->assertSame('default', $this->session->get('z', 'default'));
    }

    public function testExists(): void
    {
        $this->assertTrue($this->session->exists('a'));
        $this->assertFalse($this->session->exists('z'));
    }

    public function testSet(): void
    {
        $this->session->set('e', 'E');

        $this->assertSame('E', $this->session->get('e'));

        $this->session->set('f', 'F', false);
        $this->session->set('f', 'SpecialF', false);

        $this->assertSame('F', $this->session->get('f'));
    }

    public function testEmpty(): void
    {
        $this->assertTrue($this->session->empty('d'));
        $this->assertFalse($this->session->empty('a'));
    }

    public function testDelete(): void
    {
        $this->session->delete('a');

        $this->assertFalse($this->session->exists('a'));
    }

    public function testPush(): void
    {
        $this->session->push('c', 'c-C');

        $this->assertSame([
            'c-A',
            'c-B',
            'c-C'
        ], $this->session->get('c'));
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
            'b' => [
                'b-a' => 'b-A',
                'b-b' => 'b-B',
            ],
            'c' => [
                'c-A',
                'c-B',
            ],
            'd' => null
        ], $this->session->toArray());
    }

    public function testMergeRecursive(): void
    {
        $this->session->merge([
            'a' => 'SpecialA',
            'b' => [
                'b-a' => 'Specialb-A',
                'b-c' => 'Specialb-C'
            ],
        ]);

        $this->assertSame([
            'a' => 'SpecialA',
            'b' => [
                'b-a' => 'Specialb-A',
                'b-b' => 'b-B',
                'b-c' => 'Specialb-C',
            ],
            'c' => [
                'c-A',
                'c-B'
            ],
            'd' => null
        ], $this->session->toArray());
    }

    public function testMerge(): void
    {
        $this->session->merge([
            'a' => 'SpecialA',
            'b' => [
                'b-a' => 'Specialb-A',
                'b-c' => []
            ],
        ], false);

        $this->assertSame([
            'a' => 'SpecialA',
            'b' => [
                'b-a' => 'Specialb-A',
                'b-c' => []
            ],
            'c' => [
                'c-A',
                'c-B'
            ],
            'd' => null
        ], $this->session->toArray());
    }

    public function testExportImport(): void
    {
        $sessionArray = $this->session->toArray();

        foreach ($sessionArray as $key => $value) {
            if ($key === 'a') {
                $sessionArray['a'] = 'SpecialA';
            }
        }

        $sessionArray['b']['b-b'] = 'Specialb-B';

        $this->session->merge($sessionArray);

        $this->assertSame([
            'a' => 'SpecialA',
            'b' => [
                'b-a' => 'b-A',
                'b-b' => 'Specialb-B',
            ],
            'c' => [
                'c-A',
                'c-B',
            ],
            'd' => null
        ], $this->session->toArray());
    }

    public function testApply(): void
    {
        $result = $this->session->apply(function (Session $session, string $value) {
            return $session->get('a') . $value;
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
