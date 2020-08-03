<?php

namespace Neoflow\Session;

use Adbar\Dot;

interface SessionInterface
{

    /**
     * Destroy session
     *
     * @return bool
     */
    public function destroy(): bool;


    /**
     * Generate new session id
     *
     * @param bool $delete Set TRUE to delete old session
     * @return self
     */
    public function generateId(bool $delete = false): self;

    /**
     * Start session
     *
     * @return bool
     */
    public function start(): bool;

    /**
     * Get session id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get session name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get session status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Get session data
     *
     * @return Dot
     */
    public function getData(): Dot;
}
