<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use GuzzleHttp\Psr7\ServerRequest;

define('ROOT_DIR', dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
chdir(ROOT_DIR);

require_once 'vendor/autoload.php';

$globals = ServerRequest::fromGlobals();
$side = str_starts_with($globals->getRequestTarget(), '/back') ? 'back' : 'front';
define('TEMPLATES_DIR', sprintf('%s%s%s%s%s', ROOT_DIR, DS, 'templates', DS, $side));

$containerBuilder = (new ContainerBuilder())->useAutowiring(false);
$containerBuilder->addDefinitions('config/definitions.php');
$container = $containerBuilder->build();

$routes = require 'config/routes.php';
$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$response = (new Relay($middlewareQueue))->handle($globals);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
print $response->getBody();