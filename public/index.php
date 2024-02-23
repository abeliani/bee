<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Middleware\ErrorHandlerMiddleware;
use Abeliani\Blog\Application\Middleware\RouteDispatcherMiddleware;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\EnvLoader;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\ServerRequest;
use Relay\Relay;

define('ROOT_DIR', dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
chdir(ROOT_DIR);

require_once 'vendor/autoload.php';

(new EnvLoader())->load();

$request = ServerRequest::fromGlobals();
define('TEMPLATES_DIR', sprintf('%s%s%s', ROOT_DIR, DS, 'templates'));

$containerBuilder = (new ContainerBuilder())->useAutowiring(false);
$containerBuilder->addDefinitions('config/definitions.php');
$containerBuilder->addDefinitions('config/event_dispatcher.php');
$containerBuilder->addDefinitions('config/handlers.php');
$containerBuilder->addDefinitions('config/middlewares.php');
$containerBuilder->addDefinitions('config/repositories.php');
$containerBuilder->addDefinitions('config/services.php');
$container = $containerBuilder->build();

$routes = require 'config/routes.php';
$dispatchInfo = $routes->dispatch($request->getMethod(), $request->getUri()->getPath());
$dispatcher = $dispatchInfo[0];

if ($dispatcher === Dispatcher::NOT_FOUND || $dispatcher === Dispatcher::METHOD_NOT_ALLOWED) {
    $request = $request->withAttribute('dispatcher', $dispatcher);
    $handler = RouteDispatcherMiddleware::class;
} else {
    $handler = $dispatchInfo[1];
}

$middlewares[] = $container->get(ErrorHandlerMiddleware::class);
foreach ((new \ReflectionClass($handler))->getAttributes() as $attribute) {
    if ($attribute->getName() !== WithMiddleware::class) {
        continue;
    }
    $middlewares[] = $container->get($attribute->getArguments()[0]);
}
$middlewares[] = $container->get($handler);
$response = (new Relay($middlewares))->handle($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
print $response->getBody();
