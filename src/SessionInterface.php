<?php

namespace Neoflow\Session;

interface SessionInterface
{
    /**
     * Apply a callback with arguments to the session data
     *
     * @param callable $callback
     * @param array $args
     * @return mixed
     */
    public function apply(callable $callback, array $args = []);

    /**
     * Delete session value by key
     *
     * @param string $key
     * @return self
     */
    public function delete(string $key): self;

    /**
     * Destroy session
     *
     * @return bool
     */
    public function destroy(): bool;

    /**
     * Iterate trough the session data
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback);

    /**
     * Check whether session value is empty by key
     *
     * @param string $key
     * @return bool
     */
    public function empty(string $key): bool;

    /**
     * Check whether session value exists by key
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Get flash helper
     *
     * @return FlashInterface
     */
    public function flash(): FlashInterface;

    /**
     * Generate new session id
     *
     * @param bool $deleteOldSession
     * @return self
     */
    public function generateId(bool $deleteOldSession = false): self;

    /**
     * Get session value by key, or default value when key doesn't exists
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null);

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
     * Merge multiple keys and values of session data
     *
     * @param array $data
     * @return self
     */
    public function merge(array $data): self;

    /**
     * Push session value to the end of an indexed array by key
     *
     * @param string $key
     * @param mixed $value
     * @return SessionInterface
     */
    public function push(string $key, $value): self;

    /**
     * Set key and value of session data
     *
     * @param string $key
     * @param mixed $value
     * @param bool $overwrite
     * @return self
     */
    public function set(string $key, $value, bool $overwrite = true): self;

    /**
     * Get session data as array
     *
     * @return array
     */
    public function toArray(): array;
}
