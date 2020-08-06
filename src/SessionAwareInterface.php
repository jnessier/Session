<?php


namespace Neoflow\Session;

interface SessionAwareInterface
{
    /**
     * Set session.
     *
     * @param SessionInterface $session Session to set
     *
     * @return void
     */
    public function setSession(SessionInterface $session): void;
}
