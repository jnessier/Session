<?php

namespace Neoflow\Session;

use Neoflow\Session\Exception\SessionException;

final class Session implements SessionInterface
{

    /**
     * @var array
     */
    protected $data = [];

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
     * Constructor.
     *
     * @param array $options Session options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function clearValues(): SessionInterface
    {
        $this->data = [];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function countValues(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteValue(string $key): SessionInterface
    {
        if ($this->hasValue($key)) {
            unset($this->data[$key]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function destroy(): bool
    {
        if (!$this->isStarted()) {
            throw new SessionException('Cannot destroy the session, when the session has not started yet.');
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
            throw new SessionException('Cannot generate the session id, when the session has not started yet.');
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
    public function getValues(): array
    {
        return $this->data;
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
    public function replaceValues(array $values, bool $recursive = false): SessionInterface
    {
        if ($recursive) {
            $this->data = array_replace_recursive($this->data, $values);
        } else {
            $this->data = array_replace($this->data, $values);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setCookie(array $options): SessionInterface
    {
        if ($this->isStarted()) {
            throw new SessionException('Cannot set the cookie options, when the session has already started.');
        }

        session_set_cookie_params($this->options['cookie']);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function setName(string $name): SessionInterface
    {
        if ($this->isStarted()) {
            throw new SessionException('Cannot set the session name, when the session has already started.');
        }

        session_name($name);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setValue(string $key, $value, bool $overwrite = true): SessionInterface
    {
        if ($overwrite || !$this->hasValue($key)) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setValues(array $data): SessionInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws SessionException
     */
    public function start(): bool
    {
        if ($this->isStarted()) {
            throw new SessionException('Cannot start the session, when the session already has started.');
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
