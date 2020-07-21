<?php

namespace Neoflow\Session;

use RuntimeException;

final class Session implements SessionInterface
{
    /**
     * @var FlashInterface
     */
    protected FlashInterface $flash;

    /**
     * @var string
     */
    protected string $key = '_sessionData';

    /**
     * Constructor
     *
     * @param FlashInterface $flash
     * @param string $key
     * @throws RuntimeException
     */
    public function __construct(FlashInterface $flash, string $key = '_sessionData')
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            throw new RuntimeException('Session not started yet.');
        }

        $this->flash = $flash;
        $this->key = $key;

        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }
    }

    /**
     * Get flash helper
     *
     * @return FlashInterface
     */
    public function flash(): FlashInterface
    {
        return $this->flash;
    }

    /**
     * Get session id
     *
     * @return string
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * Generate new session id
     *
     * @param bool $deleteOldSession
     * @return self
     */
    public function generateId(bool $deleteOldSession = false): self
    {
        session_regenerate_id($deleteOldSession);

        return $this;
    }

    /**
     * Get session status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return session_status();
    }

    /**
     * Destroy session
     *
     * @return bool
     * @throws RuntimeException
     */
    public function destroy(): bool
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            return session_destroy();
        }

        throw new RuntimeException('Session already destroyed.');
    }

    /**
     * Get session name
     *
     * @return string
     */
    public function getName(): string
    {
        return session_name();
    }


    /**
     * Get session value by key, or default value when key doesn't exists
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if ($this->exists($key)) {
            return $_SESSION[$this->key][$key];
        }

        return $default;
    }

    /**
     * Check whether session value exists by key
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $_SESSION[$this->key]);
    }

    /**
     * Set key and value of session data
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, $value): self
    {
        $_SESSION[$this->key][$key] = $value;

        return $this;
    }

    /**
     * Delete session value by key
     *
     * @param string $key
     * @return self
     */
    public function delete(string $key): self
    {
        if ($this->exists($key)) {
            unset($_SESSION[$this->key][$key]);
        }

        return $this;
    }

    /**
     * Merge multiple keys and values of session data
     *
     * @param array $data
     * @return self
     */
    public function merge(array $data): self
    {
        $_SESSION[$this->key] = array_replace($_SESSION[$this->key], $data);

        return $this;
    }

    /**
     * Get session data as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $_SESSION[$this->key];
    }

    /**
     * Apply a callback with arguments to the session data
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

    /**
     * Iterate trough the session data
     *
     * @param callable $callback
     * @return mixed
     */
    public function each(callable $callback)
    {
        return $this->apply(function (Session $session) use ($callback) {
            $session = $session->toArray();

            return array_walk($session, $callback);
        });
    }
}
