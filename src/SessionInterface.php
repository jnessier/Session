<?php

namespace Neoflow\Session;

interface SessionInterface
{
    /**
     * Get flash helper
     *
     * @return FlashInterface
     */
    public function flash(): FlashInterface;

    /**
     * Get session id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Generate new session id
     *
     * @param bool $deleteOldSession
     * @return self
     */
    public function generateId(bool $deleteOldSession = false): self;

    /**
     * Get session status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Destroy session
     *
     * @return bool
     */
    public function destroy(): bool;

    /**
     * Get session name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get session value by key, or default value when key doesn't exists
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null);

    /**
     * Check whether session value exists by key
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Set key and value of session data
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, $value): self;

    /**
     * Delete session value by key
     *
     * @param string $key
     * @return self
     */
    public function delete(string $key): self;

    /**
     * Merge multiple keys and values of session data
     *
     * @param array $data
     * @return self
     */
    public function merge(array $data): self;

    /**
     * Get session data as array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Apply a callback with arguments to the session data
     *
     * @param callable $callback
     * @param array $args
     * @return mixed
     */
    public function apply(callable $callback, array $args = []);

    /**
     * Iterate trough the session data
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback);
}
