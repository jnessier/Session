<?php

namespace Neoflow\Session;

use Adbar\Dot;
use Neoflow\Session\Exception\SessionException;

class Session implements SessionInterface
{
    /**
     * @var array
     */
    protected $data;

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
     */
    public function deleteValue(string $key): void
    {
        if ($this->hasValue($key)) {
            unset($this->data[$key]);
        }
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
    public function getData(): array
    {
        if (!$this->isStarted()) {
            throw new SessionException('Session data does not exists. Session not started yet.');
        }

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function setData(array $data): void
    {
        $this->data = $data;
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
    public function getValue(string $key, $default = null)
    {
        if ($this->hasValue($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function hasValue(string $key): bool
    {
        return isset($this->data[$key]);
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
    public function mergeData(array $data, bool $recursive = true): void
    {
        if ($recursive) {
            $this->data = array_replace_recursive($this->data, $data);
        } else {
            $this->data = array_replace($this->data, $data);
        }
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
     */
    public function setValue(string $key, $value): void
    {
        $this->data[$key] = $value;
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
            $this->data = &$_SESSION;
        }

        return $result;
    }
}
