<?php

namespace Neoflow\Session\Test;

use Middlewares\Utils\Dispatcher;
use Neoflow\Session\Flash;
use Neoflow\Session\FlashInterface;
use Neoflow\Session\Middleware\SessionMiddleware;
use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    /**
     * @var FlashInterface
     */
    protected $flash;

    protected function setUp(): void
    {
        Dispatcher::run(
            [
                new SessionMiddleware(),
            ]
        );

        $_SESSION['_testFlashKey'] = [
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

        $this->flash = new Flash('_testFlashKey');

        $this->flash->setNew('a', 'A');
        $this->flash->setNew('b', [
            'b-a' => 'b-A',
            'b-b' => 'b-B',
        ]);
        $this->flash->setNew('c', [
            'c-A',
            'c-B',
        ]);
        $this->flash->setNew('d', null);
    }

    public function testExists(): void
    {
        $this->assertTrue($this->flash->exists('a'));
        $this->assertFalse($this->flash->exists('z'));
    }

    public function testGet(): void
    {
        $this->assertSame('A', $this->flash->get('a'));
        $this->assertSame([
            'b-a' => 'b-A',
            'b-b' => 'b-B',
        ], $this->flash->get('b'));
        $this->assertSame('default', $this->flash->get('z', 'default'));
    }

    public function testSetNew(): void
    {
        $this->flash->setNew('e', 'E');

        $this->assertSame('E', $this->flash->getNew('e'));

        $this->flash->setNew('f', 'F', false);
        $this->flash->setNew('f', 'SpecialF', false);

        $this->assertSame('F', $this->flash->getNew('f'));
    }

    public function testPushNew(): void
    {
        $this->flash->pushNew('c', 'c-C');

        $this->assertSame([
            'c-A',
            'c-B',
            'c-C'
        ], $this->flash->getNew('c'));
    }

    public function testEmpty(): void
    {
        $this->assertTrue($this->flash->empty('d'));
        $this->assertFalse($this->flash->empty('a'));
    }

    public function testEmptyNew(): void
    {
        $this->assertTrue($this->flash->emptyNew('d'));
        $this->assertFalse($this->flash->emptyNew('a'));
    }

    public function testDeleteNew(): void
    {
        $this->flash->deleteNew('a');

        $this->assertFalse($this->flash->existsNew('a'));
    }

    public function testExistsNew(): void
    {
        $this->assertTrue($this->flash->existsNew('a'));
        $this->assertFalse($this->flash->existsNew('z'));
    }

    public function testGetNew(): void
    {
        $this->assertSame('A', $this->flash->getNew('a'));
        $this->assertSame('default', $this->flash->getNew('z', 'default'));
    }

    public function testEach(): void
    {
        $this->flash->each(
            function ($value, $key) {
                $this->assertArrayHasKey($key, $this->flash->toArray());
                $this->assertContains($value, $this->flash->toArray());
            }
        );
    }

    public function testEachNew(): void
    {
        $this->flash->eachNew(function ($value, $key) {
            if ($key === 'a') {
                $this->assertArrayHasKey($key, $this->flash->toArrayNew());
                $this->assertContains($value, $this->flash->toArrayNew());
            }
        });
    }

    public function testMergeNewRecursive(): void
    {
        $this->flash->mergeNew([
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
        ], $this->flash->toArrayNew());
    }

    public function testMergeNew(): void
    {
        $this->flash->mergeNew([
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
        ], $this->flash->toArrayNew());
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
        ], $this->flash->toArray());
    }

    public function testToArrayNew(): void
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
        ], $this->flash->toArrayNew());
    }
}
