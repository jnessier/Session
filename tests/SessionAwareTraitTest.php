<?php


namespace Neoflow\Session\Test;

use Neoflow\Session\Session;
use Neoflow\Session\SessionAwareInterface;
use Neoflow\Session\SessionAwareTrait;
use PHPUnit\Framework\TestCase;

class SessionAwareTraitTest extends TestCase implements SessionAwareInterface
{
    use SessionAwareTrait;

    public function test(): void
    {
        $session = new Session();
        $this->setSession($session);

        $this->assertSame($session, $this->session);
    }
}
