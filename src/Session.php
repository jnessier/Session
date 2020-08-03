<?php

namespace Neoflow\Session;

use Adbar\Dot;
use Neoflow\Session\Exception\SessionException;

class Session implements SessionInterface
{
    /**
     * @var Dot
     */
    protected $data;

    /**
     * @var array
     */
    protected $options = [
        'name' => 'sid',
        'lifetime' => 3600,
        'autoRefresh' => true,
        'cookie' => [
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => true
        ]
    ];


    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(): bool
    {
        if (PHP_SESSION_NONE !== $this->getStatus()) {
            throw new SessionException('Session destroy failed. Session does not exists.');
        }

        return session_destroy();
    }

    /**
     * {@inheritDoc}
     */
    public function generateId(bool $delete = false): SessionInterface
    {
        if (PHP_SESSION_ACTIVE !== $this->getStatus()) {
            throw new SessionException('Generate session id failed. Session not started yet.');
        }

        session_regenerate_id($delete);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        if (PHP_SESSION_ACTIVE !== $this->getStatus()) {
            throw new SessionException('Session id does not exists. Session not started yet.');
        }

        return session_id();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        if (PHP_SESSION_ACTIVE !== $this->getStatus()) {
            throw new SessionException('Session name does not exists. Session not started yet.');
        }

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
    public function getData(): Dot
    {
        if (PHP_SESSION_ACTIVE !== $this->getStatus()) {
            throw new SessionException('Session data does not exists. Session not started yet.');
        }

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function start(): bool
    {
        if (PHP_SESSION_ACTIVE === $this->getStatus()) {
            throw new SessionException('Session start failed. Session already started.');
        }

        ini_set('session.gc_maxlifetime', $this->options['lifetime']);

        $cookieOptions = $this->options['cookie'];

        session_set_cookie_params($this->options['lifetime'], $cookieOptions['path'], $cookieOptions['domain'],
            $cookieOptions['secure'], $cookieOptions['httponly']);

        session_name($this->options['name']);

        $result = session_start();

        if ($this->options['autoRefresh']) {
            setcookie(session_name(), session_id(), time() + $this->options['lifetime'], $cookieOptions['path'],
                $cookieOptions['domain'], $cookieOptions['secure'], $cookieOptions['httponly']);
        }

        if ($result) {
            $this->data->setReference($_SESSION);
        }

        return $result;
    }
}
