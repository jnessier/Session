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
     * {@inheritDoc}
     */
    public function apply(callable $callback, array $args = [])
    {
        array_unshift($args, $this);

        return call_user_func_array($callback, $args);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $key): SessionInterface
    {
        $this->data->delete($key);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(): bool
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            return session_destroy();
        }

        throw new SessionException('Session already destroyed.');
    }

    /**
     * {@inheritDoc}
     */
    public function each(callable $callback)
    {
        return $this->apply(function (Session $session) use ($callback) {
            $session = $session->toArray();

            return array_walk($session, $callback);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function empty(string $key): bool
    {
        return $this->data->isEmpty($key);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $key): bool
    {
        return $this->data->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flash(): FlashInterface
    {
        return $this->flash;
    }

    /**
     * {@inheritDoc}
     */
    public function generateId(bool $deleteOldSession = false): SessionInterface
    {
        session_regenerate_id($deleteOldSession);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, $default = null)
    {
        return $this->data->get($key, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return session_name();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): int
    {
        return session_status();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function push(string $key, $value): SessionInterface
    {
        if (!is_array($this->get($key))) {
            throw new SessionException('Key "' . $key . '" does not contain an indexed array to push value.');
        }

        $this->data->push($key, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value, bool $overwrite = true): SessionInterface
    {
        if ($overwrite) {
            $this->data->set($key, $value);
        } else {
            $this->data->add($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->data->all();
    }
}
