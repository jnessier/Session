<?php

namespace Neoflow\Session\Middleware;

use Neoflow\Session\Exception\SessionException;
use Neoflow\Session\Flash;
use Neoflow\Session\Session;
use Neoflow\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionMiddleware implements MiddlewareInterface
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Process an incoming server request
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        return $handler->handle($request);
    }
}
