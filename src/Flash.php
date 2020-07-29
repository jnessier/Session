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
    public function deleteNew(string $key): FlashInterface
    {
        $this->messagesNew->delete($key);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function each(callable $callback)
    {
        return $this->apply(function (Flash $flash) use ($callback) {
            $flash = $flash->toArray();

            return array_walk($flash, $callback);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function eachNew(callable $callback)
    {
        return $this->apply(function (Flash $flash) use ($callback) {
            $flash = $flash->toArrayNew();

            return array_walk($flash, $callback);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function empty(string $key): bool
    {
        return $this->messages->isEmpty($key);
    }

    /**
     * {@inheritDoc}
     */
    public function emptyNew(string $key): bool
    {
        return $this->messagesNew->isEmpty($key);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $key): bool
    {
        return $this->messages->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function existsNew(string $key): bool
    {
        return $this->messagesNew->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, $default = null)
    {
        return $this->messages->get($key, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function getNew(string $key, $default = null)
    {
        return $this->messagesNew->get($key, $default);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function pushNew(string $key, $value): FlashInterface
    {
        if (!is_array($this->getNew($key))) {
            throw new SessionException('Key "' . $key . '" does not contain an indexed array to push value.');
        }

        $this->messagesNew->push($key, $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setNew(string $key, $value, bool $overwrite = true): FlashInterface
    {
        if ($overwrite) {
            $this->messagesNew->set($key, $value);
        } else {
            $this->messagesNew->add($key, $value);
        }


        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->messages->all();
    }

    /**
     * {@inheritDoc}
     */
    public function toArrayNew(): array
    {
        return $this->messagesNew->all();
    }
}
