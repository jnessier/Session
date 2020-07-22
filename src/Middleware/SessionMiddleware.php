<?php

namespace Neoflow\Session\Middleware;

use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Flash;
use Neoflow\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected $options = [
        'name' => 'SID',
        'lifetime' => 3600,
        'autoRefresh' => true,
        'withAttribute' => false,
        'sessionKey' => '_sessionData',
        'flashKey' => '_flashMessages',
    ];

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Process an incoming server request
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws SessionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            throw new SessionException('Session already started.');
        }

        ini_set('session.gc_maxlifetime', $this->options['lifetime']);

        session_set_cookie_params($this->options['lifetime'], '/', null, false, true);
        session_name($this->options['name']);
        session_start();

        if ($this->options['autoRefresh']) {
            setcookie(session_name(), session_id(), time() + $this->options['lifetime'], '/', null, false, true);
        }

        if ($this->options['withAttribute']) {
            $flash = new Flash($this->options['flashKey']);
            $request = $request->withAttribute('session', new Session($flash, $this->options['sessionKey']));
        }

        return $handler->handle($request);
    }
}
