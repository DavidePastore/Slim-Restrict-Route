<?php

namespace DavidePastore\Slim\RestrictRoute;

use Respect\Validation\Validator as v;

/**
 * RestrictRoute for Slim.
 */
class RestrictRoute
{
    protected $options = [
      'ip' => null,
    ];

    /**
     * Create new RestrictRoute service provider.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * RestrictRoute middleware invokable class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $ipAddress = $request->getAttribute('ip_address');
        if ($this->options['ip'] && !v::ip($this->options['ip'])->validate($ipAddress)) {
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }

    /**
     * Get the options array.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options array.
     *
     * @param array $options The options array.
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}
