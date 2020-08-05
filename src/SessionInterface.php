<?php

namespace Neoflow\Session;

use Neoflow\Data\DataInterface;

interface SessionInterface
{


    /**
     * Destroy session.
     *
     * @link https://php.net/manual/en/function.session-destroy.php
     *
     * @return bool
     */
    public function destroy(): bool;

    /**
     * Generate new session id.
     *
     * @link https://php.net/manual/en/function.session-regenerate-id.php
     *
     * @param bool $delete Set TRUE to delete old session
     *
     * @return string
     */
    public function generateId(bool $delete = false): string;

    /**
     * Get session cookie.
     *
     * @link https://www.php.net/manual/en/function.session-get-cookie-params.php
     *
     * @return array
     */
    public function getCookie(): array;


    /**
     * Get session id.
     *
     * @link https://www.php.net/manual/en/function.session-id.php
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get session name.
     *
     * @link https://www.php.net/manual/en/function.session-name.php
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get session status.
     *
     * @link https://www.php.net/manual/en/function.session-status.php
     *
     * @return int
     */
    public function getStatus(): int;


    /**
     * Check whether session is started.
     *
     * @return bool
     */
    public function isStarted(): bool;


    /**
     * Set session cookie.
     *
     * @link https://www.php.net/manual/en/function.session-set-cookie-params.php
     *
     * @param array $options Cookie options
     */
    public function setCookie(array $options): void;

    /**
     * Set session name.
     *
     * @param string $name Session name
     */
    public function setName(string $name): void;


    /**
     * Start session.
     *
     * @return bool
     */
    public function start(): bool;
}
