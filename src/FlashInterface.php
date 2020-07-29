<?php

namespace Neoflow\Session;

interface FlashInterface
{
    /**
     * Apply a callback with arguments to the flash helper
     *
     * @param callable $callback
     * @param array $args
     * @return mixed
     */
    public function apply(callable $callback, array $args = []);

    /**
     * Delete new flash message by key.
     *
     * @param string $key
     * @return self
     */
    public function deleteNew(string $key): self;

    /**
     * Iterate trough the flash messages
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback);

    /**
     * Iterate trough the new flash messages.
     *
     * @param callable $callback
     * @return mixed
     */
    public function eachNew(callable $callback);

    /**
     * Check whether flash message is empty by key
     *
     * @param string $key
     * @return bool
     */
    public function empty(string $key): bool;

    /**
     * Check whether flash message is empty by key
     *
     * @param string $key
     * @return bool
     */
    public function emptyNew(string $key): bool;

    /**
     * Get flash message by key, or default value when the key doesn't exists
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Check whether new flash message exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function existsNew(string $key): bool;

    /**
     * Check whether flash message exists by key
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null);

    /**
     * Get new flash message by key, or default value when the key doesn't exists.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getNew(string $key, $default = null);

    /**
     * Merge multiple keys and values of flash messages.
     *
     * @param array $messages
     * @return self
     */
    public function mergeNew(array $messages): self;

    /**
     * Push flash message to the end of an indexed array by key
     *
     * @param string $key
     * @param mixed $value
     * @return FlashInterface
     */
    public function pushNew(string $key, $value): self;

    /**
     * Set key and value of new flash message.
     *
     * @param string $key
     * @param mixed $value
     * @param bool $overwrite
     * @return self
     */
    public function setNew(string $key, $value, bool $overwrite = true): self;

    /**
     * Get flash messages as array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Get new flash messages as array.
     *
     * @return array
     */
    public function toArrayNew(): array;
}
