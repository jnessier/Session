<?php

namespace Neoflow\Session;

use Adbar\Dot;
use Neoflow\Session\Exception\SessionException;

class Flash implements FlashInterface
{
    /**
     * @var Dot
     */
    protected $messages;
    protected $messagesNew;

    /**
     * Constructor.
     *
     * @param string $key
     * @throws SessionException
     */
    public function __construct(string $key = '_flashMessages')
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            throw new SessionException('Session not started yet.');
        }

        $this->messages = new Dot();
        $this->messagesNew = new Dot();

        if (isset($_SESSION[$key])) {
            $this->messages->setArray($_SESSION[$key]);
        }
        $_SESSION[$key] = [];

        $this->messagesNew = new Dot();
        $this->messagesNew->setReference($_SESSION[$key]);
    }

    /**
     * Check whether flash message exists by key
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->messages->get($key, $default);
    }

    /**
     * Get flash message by key, or default value when the key doesn't exists
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->messages->has($key);
    }

    /**
     * Get flash messages as array (set in previous request)
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->messages->all();
    }

    /**
     * Iterate trough the flash messages
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback)
    {
        return $this->apply(function (Flash $flash) use ($callback) {
            $flash = $flash->toArray();

            return array_walk($flash, $callback);
        });
    }

    /**
     * Set key and value of new flash message.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setNew(string $key, $value): FlashInterface
    {
        $this->messagesNew->set($key, $value);

        return $this;
    }

    /**
     * Check whether new flash message exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function existsNew(string $key): bool
    {
        return $this->messagesNew->has($key);
    }

    /**
     * Get new flash message by key, or default value when the key doesn't exists.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getNew(string $key, $default = null)
    {
        return $this->messagesNew->get($key, $default);
    }

    /**
     * Delete new flash message by key.
     *
     * @param string $key
     * @return self
     */
    public function deleteNew(string $key): FlashInterface
    {
        $this->messagesNew->delete($key);

        return $this;
    }

    /**
     * Merge multiple keys and values of flash messages.
     *
     * @param array $messages
     * @param bool $recursive
     * @return self
     */
    public function mergeNew(array $messages, bool $recursive = true): FlashInterface
    {
        if ($recursive) {
            $this->messagesNew->mergeRecursiveDistinct($messages);
        } else {
            $this->messagesNew->merge($messages);
        }

        return $this;
    }

    /**
     * Get new flash messages as array.
     *
     * @return array
     */
    public function toArrayNew(): array
    {
        return $this->messagesNew->all();
    }

    /**
     * Iterate trough the new flash messages.
     *
     * @param callable $callback
     * @return mixed
     */
    public function eachNew(callable $callback)
    {
        return $this->apply(function (Flash $flash) use ($callback) {
            $flash = $flash->toArrayNew();

            return array_walk($flash, $callback);
        });
    }

    /**
     * Apply a callback with arguments to the flash helper
     *
     * @param callable $callback
     * @param array $args
     * @return mixed
     */
    public function apply(callable $callback, array $args = [])
    {
        array_unshift($args, $this);

        return call_user_func_array($callback, $args);
    }
}
