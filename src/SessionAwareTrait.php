<?php

namespace Neoflow\Session;

trait SessionAwareTrait
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * {@inheritDoc}
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
