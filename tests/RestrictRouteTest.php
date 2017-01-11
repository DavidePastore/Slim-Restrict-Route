<?php

namespace DavidePastore\Slim\RestrictRoute\Tests;

use DavidePastore\Slim\RestrictRoute\RestrictRoute;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

class RestrictRouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * PSR7 request object.
     *
     * @var Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * PSR7 response object.
     *
     * @var Psr\Http\Message\ResponseInterface
     */
    protected $response;

    protected $headers;

    protected $serverParams;

    protected $body;

    /**
     * Run before each test.
     */
    public function setUp()
    {
        $uri = Uri::createFromString('https://example.com:443/foo/bar');
        $this->headers = new Headers();
        $this->headers->set('REMOTE_ADDR', '127.0.0.1');
        $this->cookies = [];
        $env = Environment::mock();
        $this->serverParams = $env->all();
        $this->body = new Body(fopen('php://temp', 'r+'));
        $this->response = new Response();
        $this->request = new Request('GET', $uri, $this->headers, $this->cookies, $this->serverParams, $this->body);
    }

    /**
     * @dataProvider locationProvider
     */
    public function testLocation($url)
    {
        $options = array(
          'ip' => '192.*',
        );
        $mw = new RestrictRoute($options);

        $next = function ($req, $res) {
            return $res;
        };
        $uri = Uri::createFromString('https://example.com:443/foo/bar?redirect=' . $url);
        $this->request = new Request('GET', $uri, $this->headers, $this->cookies, $this->serverParams, $this->body);
        $redirect = $mw($this->request, $this->response, $next);
        $location = $redirect->getHeader('Location');
        $this->assertEquals($redirect->getStatusCode(), 200);
        $this->assertEquals(count($location), 1);
        $this->assertEquals($location[0], $url);
    }

    public function locationProvider(){
      return [
        ['http://www.google.it'],
        ['http://stackoverflow.com/'],
      ];
    }
}
