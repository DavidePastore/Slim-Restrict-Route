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

    /**
     * Run before each test.
     */
    public function setUp()
    {
        $uri = Uri::createFromString('https://example.com:443/foo/bar');
        $headers = new Headers();
        $headers->set('REMOTE_ADDR', '127.0.0.1');
        $cookies = [];
        $env = Environment::mock();
        $serverParams = $env->all();
        $body = new Body(fopen('php://temp', 'r+'));
        $this->request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        $this->response = new Response();
    }

    public function testRestrictedRoute()
    {
        $options = array(
          'ip' => '192.*',
        );
        $ipAddressMw = new \RKA\Middleware\IpAddress();
        $mw = new RestrictRoute($options);

        $next = function ($req, $res) {
            return $res;
        };

        $nextRequest = function ($req, $res) {
            return $req;
        };

        $this->request = $ipAddressMw($this->request, $this->response, $nextRequest);
        $response = $mw($this->request, $this->response, $next);
        $expected = 401;
        $this->assertEquals($response->getStatusCode(), $expected);
    }

    public function testRestrictedRouteWithRange()
    {
        $options = array(
          'ip' => '192.0.0.0-192.255.255.255',
        );
        $ipAddressMw = new \RKA\Middleware\IpAddress();
        $mw = new RestrictRoute($options);

        $next = function ($req, $res) {
            return $res;
        };

        $nextRequest = function ($req, $res) {
            return $req;
        };

        $this->request = $ipAddressMw($this->request, $this->response, $nextRequest);
        $response = $mw($this->request, $this->response, $next);
        $expected = 401;
        $this->assertEquals($response->getStatusCode(), $expected);
    }

    public function testNotRestrictedRoute()
    {
        $options = array(
          'ip' => '127.0.0.*',
        );
        $ipAddressMw = new \RKA\Middleware\IpAddress();
        $mw = new RestrictRoute($options);

        $next = function ($req, $res) {
            return $res;
        };

        $nextRequest = function ($req, $res) {
            return $req;
        };

        $this->request = $ipAddressMw($this->request, $this->response, $nextRequest);
        $response = $mw($this->request, $this->response, $next);
        $expected = 200;
        $this->assertEquals($response->getStatusCode(), $expected);
    }

    public function testSetOptions()
    {
        $options = array(
          'ip' => '127.*',
        );
        $ipAddressMw = new \RKA\Middleware\IpAddress();
        $mw = new RestrictRoute($options);

        $next = function ($req, $res) {
            return $res;
        };

        $nextRequest = function ($req, $res) {
            return $req;
        };

        $this->request = $ipAddressMw($this->request, $this->response, $nextRequest);
        $response = $mw($this->request, $this->response, $next);
        $expectedOptions = array(
          'ip' => '192.168.0.1',
        );
        $mw->setOptions($expectedOptions);
        $options = $mw->getOptions();
        $this->assertEquals($options, $expectedOptions);
    }
}
