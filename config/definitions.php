<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Service\Image\Args\Size;
use Abeliani\Blog\Application\Service\Image\Builder\ImageQueryBuilder;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Crop;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Resize;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Save;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Strip;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
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
    ImageQueryBuilder::class => function(): ImageQueryBuilder {
        $upload =  ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR');

        $thumb = new ImageQueryBuilder('thumb');
        $thumb->append(new Resize(new Size(60.0, 40.0)))
            ->append(new Save($upload . DS . date('Y') . DS . uniqid(), IMAGETYPE_JPEG));

        $resized = new ImageQueryBuilder('view');
        $resized->append(function (ProcessorContext $c) {
            return Crop::build($c->get('width'), $c->get('height'), $c->get('x'), $c->get('y'));
        }, Crop::getName())
            ->append(new Resize(new Size(600.0,400.0)))
            ->append(new Save($upload . DS . date('Y') . DS . uniqid(), IMAGETYPE_JPEG));

        return (new ImageQueryBuilder('original'))
            ->append(new Strip())
            ->append(new Save($upload . DS . 'images/original' . DS . uniqid(), IMAGETYPE_JPEG))
            ->branch($resized)
            ->branch($thumb);
    },
];
