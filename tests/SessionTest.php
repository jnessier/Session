<?php

namespace Neoflow\Session\Test;

use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @runInSeparateProcess
     */
    protected function setUp(): void
    {
        $this->session = new Session([
            'iniSettings' => [
                'gc_maxlifetime' => 1440
            ]
        ]);

        $this->session->start();

        $_SESSION = [
            'a' => 'A',
            'b' => [
                'b-A',
            ],
            'c' => [
                'c-a' => 'C-A',
                'c-b' => [
                    'c-b-a' => 'C-B-A'
                ]
            ]
        ];
    }

    /**
     * @runInSeparateProcess
     */
    public function testClearValues(): void
    {
        $this->session->clearValues();

        $this->assertSame([], $this->session->getValues());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCountValues(): void
    {
        $this->assertSame(3, $this->session->countValues());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDeleteValue(): void
    {
        $this->session->deleteValue('a');

        $this->assertFalse($this->session->hasValue('a'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroy(): void
    {
        $this->assertTrue($this->session->destroy());
        $this->assertSame(PHP_SESSION_NONE, $this->session->getStatus());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGenerateId(): void
    {
        $oldId = $this->session->getId();
        $this->session->generateId();

        $this->assertNotSame($oldId, $this->session->getId());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetCookie(): void
    {
        $this->assertSame([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ], $this->session->getCookie());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetId(): void
    {
        $this->assertIsString($this->session->getId());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetName(): void
    {
        $this->assertSame('sid', $this->session->getName());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetStatus(): void
    {
        $this->assertSame(PHP_SESSION_ACTIVE, $this->session->getStatus());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetValue(): void
    {
        $this->assertSame('A', $this->session->getValue('a'));
        $this->assertSame('default', $this->session->getValue('z', 'default'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetValues(): void
    {
        $this->assertSame($_SESSION, $this->session->getValues());
    }

    /**
     * @runInSeparateProcess
     */
    public function testHasValue(): void
    {
        $this->assertTrue($this->session->hasValue('a'));
        $this->assertFalse($this->session->hasValue('z'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testReplaceAllRecursively(): void
    {
        $this->session->replaceValues([
            'a' => 'SpecialA',
            'c' => [
                'c-c' => []
            ]
        ], true);

        $this->assertSame('SpecialA', $this->session->getValue('a'));
        $this->assertSame([
            'b-A',
        ], $this->session->getValue('b'));
        $this->assertSame([
            'c-a' => 'C-A',
            'c-b' => [
                'c-b-a' => 'C-B-A'
            ],
            'c-c' => []
        ], $this->session->getValue('c'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testReplaceValues(): void
    {
        $this->session->replaceValues([
            'a' => 'SpecialA',
            'c' => [
                'c-c' => []
            ]
        ], false);

        $this->assertSame('SpecialA', $this->session->getValue('a'));
        $this->assertSame([
            'b-A',
        ], $this->session->getValue('b'));
        $this->assertSame([
            'c-c' => []
        ], $this->session->getValue('c'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetValue(): void
    {
        $this->session->setValue('d', 'D');

        $this->session->setValue('e', 'E', false);
        $this->session->setValue('d', 'SpecialD', false);

        $this->assertSame('D', $this->session->getValue('d'));
        $this->assertSame('E', $this->session->getValue('e'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetValues(): void
    {
        $values = [
            'a' => 'A'
        ];
        $this->session->setValues($values);
        $this->session->setValue('a', 'SpecialA');

        $this->assertNotSame($values, $this->session->getValues());
        $this->assertSame([
            'a' => 'SpecialA'
        ], $this->session->getValues());
    }
}
