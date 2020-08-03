<?php

namespace Neoflow\Session;


trait SessionAwareTrait
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * Set session
     *
     * @param SessionInterface $session
     * @return void
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
