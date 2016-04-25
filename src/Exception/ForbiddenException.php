<?php

namespace DavidePastore\Slim\RestrictRoute\Exception;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\SlimException;

class ForbiddenException extends SlimException
{
    /**
     * Create new exception.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }
}
