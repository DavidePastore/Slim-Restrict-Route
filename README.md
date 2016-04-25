# Slim Framework Restrict Route

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


A slim middleware to restrict ip addresses that will access to your routes. It internally uses `Ip` Validator of [Respect/Validation][respect-validation] and [rka-ip-address-middleware][rka-ip-address-middleware].

## Install

Via Composer

``` bash
$ composer require davidepastore/slim-restrict-route
```

Requires Slim 3.0.0 or newer.

## Usage

You have to register also the [`RKA\Middleware\IpAddress`][rka-ip-address-middleware] middleware to correctly read the ip address.
In most cases you want to register `DavidePastore\Slim\RestrictRoute` for a single route, however,
as it is middleware, you can also register it for all routes.


### Register per route

```php
$app = new \Slim\App();

$app->add(new RKA\Middleware\IpAddress());

$options = array(
  'ip' => '192.*.*.*'
);

$app->get('/api/myEndPoint',function ($req, $res, $args) {
  //Your amazing route code
})->add(new \DavidePastore\Slim\RestrictRoute\RestrictRoute($options));

$app->run();
```


### Register for all routes

```php
$app = new \Slim\App();

$app->add(new RKA\Middleware\IpAddress());

$options = array(
  'ip' => '192.*.*.*'
);

// Register middleware for all routes
// If you are implementing per-route checks you must not add this
$app->add(new \DavidePastore\Slim\RestrictRoute\RestrictRoute($options));

$app->get('/foo', function ($req, $res, $args) {
  //Your amazing route code
});

$app->post('/bar', function ($req, $res, $args) {
  //Your amazing route code
});

$app->run();
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Davide Pastore](https://github.com/davidepastore)


[respect-validation]: https://github.com/Respect/Validation/
[rka-ip-address-middleware]: https://github.com/akrabat/rka-ip-address-middleware
[ico-version]: https://img.shields.io/packagist/v/DavidePastore/Slim-Restrict-Route.svg?style=flat-square
[ico-travis]: https://travis-ci.org/DavidePastore/Slim-Restrict-Route.svg?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DavidePastore/Slim-Restrict-Route.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/davidepastore/Slim-Restrict-Route.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/davidepastore/slim-restrict-route.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/davidepastore/slim-restrict-route
[link-travis]: https://travis-ci.org/DavidePastore/Slim-Restrict-Route
[link-scrutinizer]: https://scrutinizer-ci.com/g/DavidePastore/Slim-Restrict-Route/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DavidePastore/Slim-Restrict-Route
[link-downloads]: https://packagist.org/packages/davidepastore/slim-restrict-route
