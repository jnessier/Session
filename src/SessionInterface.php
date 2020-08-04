<?php

namespace Neoflow\Session;

use Adbar\Dot;

interface SessionInterface
{

    /**
     * Delete session value by key.
     *
     * @param string $key Key as identifier of the value
     */
    public function deleteValue(string $key): void;

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
     * @return self
     */
    public function generateId(bool $delete = false): self;

    /**
     * Get session data.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Check whether session value exists by key.
     *
     * @param string $key Key as identifier
     *
     * @return bool
     */
    public function hasValue(string $key): bool;

    /**
     * Set session data. Already set data will be overwritten.
     *
     * @param array $data Session data
     */
    public function setData(array $data): void;

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
     * Get session value by key.
     *
     * @param string $key Key as identifier
     * @param mixed $default Default value when key doesn't exists
     *
     * @return mixed
     */
    public function getValue(string $key, $default = null);

    /**
     * Check whether session is started.
     *
     * @return bool
     */
    public function isStarted(): bool;

    /**
     * Merge data into session data
     *
     * @param array $data Data
     * @param bool $recursive Set FALSE to disable recursive merge
     */
    public function merge(array $data, bool $recursive = true): void;

    /**
     * Set session cookie
     *
     * @link https://www.php.net/manual/en/function.session-set-cookie-params.php
     *
     * @param array $options Cookie options
     */
    public function setCookie(array $options): void;

    /**
     * Get session cookie.
     *
     * @link https://www.php.net/manual/en/function.session-get-cookie-params.php
     *
     * @return array
     */
    public function getCookie(): array;

    /**
     * Set session name.
     *
     * @param string $name Session name
     */
    public function setName(string $name): void;

    /**
     * Set session value by key.
     *
     * @param string $key Key as identifier
     * @param mixed $value Session value
     */
    public function setValue(string $key, $value): void;

    /**
     * Start session.
     *
     * @return bool
     */
    public function start(): bool;
}
