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

        Dispatcher::run([
            new SessionMiddleware($this->session),
        ]);

        $_SESSION = [
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
    }

    public function testGetValue(): void
    {
        $this->assertSame('A', $this->session->getValue('a'));
        $this->assertSame('default', $this->session->getValue('z', 'default'));
    }

    public function testHasValue(): void
    {
        $this->assertTrue($this->session->hasValue('a'));
        $this->assertFalse($this->session->hasValue('z'));
    }

    public function testSetValue(): void
    {
        $this->session->setValue('e', 'E');

        $this->assertSame('E', $this->session->getValue('e'));
    }

    public function testDeleteValue(): void
    {
        $this->session->deleteValue('a');

        $this->assertFalse($this->session->hasValue('a'));
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

        $this->expectException(SessionException::class);

        $this->session->destroy();
    }

    public function testGetStatus(): void
    {
        $this->assertSame(PHP_SESSION_ACTIVE, $this->session->getStatus());
    }

    public function testGetNameInvalid(): void
    {
        $this->session->destroy();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session name does not exists. Session not started yet.');

        $this->session->getName();
    }

    public function testStartInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session start failed. Session already started.');

        $this->session->start();
    }

    public function testSetNameInvalid(): void
    {
        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Set session name failed. Session already started.');

        $this->session->setName('foo bar');
    }

    public function testGenerateIdInvalid(): void
    {
        $this->session->destroy();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Generate session id failed. Session not started yet.');

        $this->session->generateId();
    }

    public function testGetDataInvalid(): void
    {
        $this->session->destroy();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session data does not exists. Session not started yet.');

        $this->session->getData();
    }

    public function testGetIdInvalid(): void
    {
        $this->session->destroy();

        $this->expectException(SessionException::class);
        $this->expectExceptionMessage('Session id does not exists. Session not started yet.');

        $this->session->getId();
    }

    public function testGetCookie(): void
    {
        $this->assertIsArray($this->session->getCookie());
    }

    public function testGetName(): void
    {
        $this->assertSame('sid', $this->session->getName());
    }

    public function testToArray(): void
    {
        $this->assertSame($_SESSION, $this->session->getData());
    }

    public function testMergeDataRecursive(): void
    {
        $this->session->mergeData([
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
        ], $this->session->getData());
    }

    public function testMergeData(): void
    {
        $this->session->mergeData([
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
        ], $this->session->getData());
    }

    public function testSetData(): void
    {
        $this->session->setData([
            'foo' => 'bar'
        ]);

        $this->assertSame([
            'foo' => 'bar'
        ], $this->session->getData());
    }

}
