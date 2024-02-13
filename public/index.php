<?php

declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
chdir(ROOT_DIR);

require_once 'vendor/autoload.php';

use DI\ContainerBuilder;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use GuzzleHttp\Psr7\ServerRequest;

$containerBuilder = (new ContainerBuilder())->useAutowiring(false);
$containerBuilder->addDefinitions('config/definitions.php');
$container = $containerBuilder->build();

$routes = require 'config/routes.php';
$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$response = (new Relay($middlewareQueue))->handle(ServerRequest::fromGlobals());

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
print $response->getBody();