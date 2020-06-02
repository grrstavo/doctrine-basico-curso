<?php

use App\Entity\Category;
use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
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

$generator = $routerConatiner->getGenerator();
$map = $routerConatiner->getMap();

$view = new PhpRenderer(__DIR__. '/../templates/');

$entityManager = getEntityManager();

$map->get('home', '/home', function($request, $response) use ($view) {
    return $view->render($response, 'home.phtml', [
        'test' => 'Slim PHP View funcionando!'
    ]);
});

require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/posts.php';

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

if ($response instanceof Response\RedirectResponse) {
    header("location: {$response->getHeader("location")[0]}");
} elseif ($response instanceof Response) {
    echo $response->getBody();
}