<?php

use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use Neoflow\Session\Flash;
use Neoflow\Session\Middleware\SessionMiddleware;
use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    protected Flash $flash;

    protected function setUp(): void
    {
        Dispatcher::run([
            new SessionMiddleware(),
        ]);

        $_SESSION['_testFlashKey'] = [
            'a' => 'A',
        ];

        $this->flash = new Flash('_testFlashKey');

        $this->flash->setNew('b', 'B');
    }

    public function testExists()
    {
        $this->assertTrue($this->flash->exists('a'));
        $this->assertFalse($this->flash->exists('c'));
    }

    public function testGet()
    {
        $this->assertSame('A', $this->flash->get('a'));
        $this->assertSame('default', $this->flash->get('b', 'default'));
    }

    public function testSetNew()
    {
        $this->flash->setNew('c', 'C');

        $this->assertSame('C', $this->flash->getNew('c'));
        $this->assertTrue($this->flash->existsNew('c'));
    }

    public function testDeleteNew()
    {
        $this->flash->deleteNew('b');

        $this->assertFalse($this->flash->existsNew('a'));
    }

    public function testExistsNew()
    {
        $this->assertTrue($this->flash->existsNew('b'));
        $this->assertFalse($this->flash->existsNew('c'));
    }

    public function testGetNew()
    {
        $this->assertSame('B', $this->flash->getNew('b'));
        $this->assertSame('default', $this->flash->getNew('c', 'default'));
    }

    public function testEach()
    {
        $this->flash->each(function ($value, $key) {
            $this->assertSame('a', $key);
            $this->assertSame('A', $value);
        });
    }

    public function testEachNew()
    {
        $this->flash->eachNew(function ($value, $key) {
            $this->assertSame('b', $key);
            $this->assertSame('B', $value);
        });
    }

    public function testMergeNewRecursive()
    {
        $this->flash->setNew('c', [
            'd' => 'D',
            'e' => 'E'
        ]);

        $this->flash->mergeNew([
            'b' => 'SpecialB',
            'c' => [
                'e' => 'SpecialE',
            ]
        ]);

        $this->assertSame([
            'b' => 'SpecialB',
            'c' => [
                'd' => 'D',
                'e' => 'SpecialE'
            ],
        ], $this->flash->toArrayNew());
    }

    public function testMergeNew()
    {
        $this->flash->setNew('c', [
            'd' => 'D',
            'e' => 'E'
        ]);

        $this->flash->mergeNew([
            'b' => 'SpecialB',
            'c' => [
                'e' => 'SpecialE',
            ]
        ], false);

        $this->assertSame([
            'b' => 'SpecialB',
            'c' => [
                'e' => 'SpecialE'
            ],
        ], $this->flash->toArrayNew());
    }

    public function testToArray()
    {
        $this->assertSame([
            'a' => 'A',
        ], $this->flash->toArray());
    }

    public function testToArrayNew()
    {
        $this->flash->setNew('b', 'B');

        $this->assertSame([
            'b' => 'B',
        ], $this->flash->toArrayNew());
    }
}
