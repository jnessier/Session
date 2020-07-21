<?php

namespace Neoflow\Session;

use RuntimeException;

interface FlashInterface
{
    /**
    * Check whether flash message exists by key
    *
    * @param string $key
    * @param mixed|null $default
    * @return mixed|null
    */
    public function get(string $key, $default = null);

    /**
     * Get flash message by key, or default value when the key doesn't exists
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Get flash messages as array (set in previous request)
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Iterate trough the flash messages
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback);

    /**
     * Set key and value of new flash message.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setNew(string $key, $value): self;

    /**
     * Check whether new flash message exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function existsNew(string $key): bool;

    /**
     * Get new flash message by key, or default value when the key doesn't exists.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getNew(string $key, $default = null);

    /**
     * Delete new flash message by key.
     *
     * @param string $key
     * @return self
     */
    public function deleteNew(string $key): self;

    /**
     * Merge multiple keys and values of flash messages.
     *
     * @param array $messages
     * @return self
     */
    public function mergeNew(array $messages): self;

    /**
     * Get new flash messages as array.
     *
     * @return array
     */
    public function toArrayNew(): array;

    /**
     * Iterate trough the new flash messages.
     *
     * @param callable $callback
     * @return mixed
     */
    public function eachNew(callable $callback);

    /**
     * Apply a callback with arguments to the flash helper
     *
     * @param callable $callback
     * @param array $args
     * @return mixed
     */
    public function apply(callable $callback, array $args = []);
}
