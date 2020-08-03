<?php


namespace Neoflow\Session;

interface SessionAwareInterface
{

    /**
     * Set session
     *
     * @param SessionInterface $session
     * @return void
     */
    public function setSession(SessionInterface $session): void;
}
