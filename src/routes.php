<?php

use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerConatiner = new RouterContainer();

$map = $routerConatiner->getMap();
$map->get('home', '/home', function($request, $response) {
    $response->getBody()->write('Hello Word!');
    return $response;
});

$matcher = $routerConatiner->getMatcher();
$route = $matcher->match($request);

foreach ($route->attributes as $key => $value) {
    $request = $request->withAttribute($key, $value);
}

$callable = $route->handler;

/**
 * @var Response $response
 */
$response = $callable($request, new Response());

echo $response->getBody();