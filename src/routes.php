<?php

use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Slim\Views\PhpRenderer;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerConatiner = new RouterContainer();

$map = $routerConatiner->getMap();

$view = new PhpRenderer(__DIR__. '/../templates/');

$map->get('home', '/home', function($request, $response) use ($view) {
    return $view->render($response, 'home.phtml', [
        'test' => 'Slim PHP View funcionando!'
    ]);
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