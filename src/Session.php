<?php

namespace Neoflow\Session;

use Neoflow\Data\AbstractData;
use Neoflow\Session\Exception\SessionException;

class Session extends AbstractData implements SessionInterface
{
    /**
     * @var array
     */
    protected $options = [
        'name' => 'sid',
        'autoRefresh' => true,
        'cookie' => [
            'lifetime' => 3600,
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ],
        'iniSettings' => []
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
     *
     * @throws SessionException
     */
    public function destroy(): bool
    {
        if (!$this->isStarted()) {
            throw new SessionException('Destroy session failed. Session not started yet.');
        }

        return session_destroy();
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function generateId(bool $delete = false): string
    {
        if (!$this->isStarted()) {
            throw new SessionException('Generate session id failed. Session not started yet.');
        }

        session_regenerate_id($delete);

        return $this->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getCookie(): array
    {
        return session_get_cookie_params();
    }


    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function getId(): string
    {
        if (!$this->isStarted()) {
            throw new SessionException('Session id does not exists. Session not started yet.');
        }

        return session_id();
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function getName(): string
    {
        if (!$this->isStarted()) {
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
    public function isStarted(): bool
    {
        return PHP_SESSION_ACTIVE === $this->getStatus();
    }


    /**
     * {@inheritDoc}
     */
    public function setCookie(array $options): void
    {
        session_set_cookie_params($this->options['cookie']);
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function setName(string $name): void
    {
        if ($this->isStarted()) {
            throw new SessionException('Set session name failed. Session already started.');
        }

        session_name($name);
    }


    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function start(): bool
    {
        if ($this->isStarted()) {
            throw new SessionException('Session start failed. Session already started.');
        }

        if (is_array($this->options['iniSettings'])) {
            foreach ($this->options['iniSettings'] as $key => $value) {
                $key = (string)$key;
                if (strlen($key)) {
                    ini_set('session.' . $key, $value);
                }
            }
        }

        $cookieOptions = $this->options['cookie'];
        if ($this->options['autoRefresh']) {
            $cookieOptions['lifetime'] = (int)$cookieOptions['lifetime'] + time();
        }
        $this->setCookie($cookieOptions);

        $this->setName($this->options['name']);

        $result = session_start();

        if ($result) {
            $this->values = &$_SESSION;
        }

        return $result;
    }
}
