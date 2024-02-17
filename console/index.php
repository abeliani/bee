<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

const NS = 'Abeliani\\Blog\\Console\\Command';
define('ROOT_DIR', dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
chdir(ROOT_DIR);
define('TEMPLATES_DIR', sprintf('%s%s%s%s%s', ROOT_DIR, DS, 'templates', DS, 'back'));

require_once 'vendor/autoload.php';

$containerBuilder = (new ContainerBuilder())->useAutowiring(false);
$containerBuilder->addDefinitions('config/definitions.php');
$container = $containerBuilder->build();

$console = new Application();
$commandDirectory = ROOT_DIR . '/src/Console/Command';
$directory = new RecursiveDirectoryIterator($commandDirectory, FilesystemIterator::SKIP_DOTS);

foreach (new RecursiveIteratorIterator($directory) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $class = sprintf('%s\\%s', NS, $file->getBasename('.php'));
        if (is_subclass_of($class, Command::class)) {
            $console->add($container->get($class));
        }
    }
}

$console->run();