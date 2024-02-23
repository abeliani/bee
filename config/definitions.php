<?php
declare(strict_types=1);

use Monolog\Handler\FilterHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    Environment::class => function(): Environment {
        return new Environment(new FilesystemLoader(TEMPLATES_DIR));
    },
    PDO::class => function (): PDO {
        return new PDO('mysql:host=db;dbname=bee', 'root', 'root', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => true
        ]);
    },
    LoggerInterface::class => function(): LoggerInterface {
        $debugHandler = new RotatingFileHandler(ROOT_DIR . DS . getenv('APP_LOG_PATH'), 3, Level::Debug, false);
        $restHandler = new RotatingFileHandler( ROOT_DIR . DS . getenv('DEBUG_LOG_PATH'), 3, Level::Warning, false);
        return (new Logger('app'))
            ->pushHandler(new FilterHandler($debugHandler, Level::Debug, Level::Notice))
            ->pushHandler(new FilterHandler($restHandler, Level::Warning, Level::Emergency));
    },
];
