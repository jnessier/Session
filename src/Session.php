<?php

namespace Neoflow\Session;

use Adbar\Dot;
use Neoflow\Session\Exception\SessionException;

class Session implements SessionInterface
{
    /**
     * @var FlashInterface
     */
    protected $flash;

    /**
     * @var Dot
     */
    protected $data;

    /**
     * Constructor
     *
     * @param FlashInterface $flash
     * @param string $key
     * @throws SessionException
     */
    public function __construct(FlashInterface $flash, string $key = '_sessionData')
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            throw new SessionException('Session not started yet.');
        }

        $this->flash = $flash;

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }

        $this->data = new Dot();
        $this->data->setReference($_SESSION[$key]);
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
    public function generateId(bool $deleteOldSession = false): SessionInterface
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
     * @throws SessionException
     */
    public function destroy(): bool
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            return session_destroy();
        }

        throw new SessionException('Session already destroyed.');
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
        return $this->data->get($key, $default);
    }

    /**
     * Check whether session value exists by key
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->data->has($key);
    }

    /**
     * Set key and value of session data
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, $value): SessionInterface
    {
        $this->data->set($key, $value);

        return $this;
    }

    /**
     * Delete session value by key
     *
     * @param string $key
     * @return self
     */
    public function delete(string $key): SessionInterface
    {
        $this->data->delete($key);

        return $this;
    }

    /**
     * Merge multiple keys and values of session data
     *
     * @param array $data
     * @param bool $recursive
     * @return self
     */
    public function merge(array $data, bool $recursive = true): SessionInterface
    {
        if ($recursive) {
            $this->data->mergeRecursiveDistinct($data);
        } else {
            $this->data->merge($data);
        }

        return $this;
    }

    /**
     * Get session data as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data->all();
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
