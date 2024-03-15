<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Enum\ConfigDi;
use Abeliani\Blog\Application\Service\Image\Args\Size;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Brightness;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Contrast;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Discolor;
use Abeliani\Blog\Application\Service\Image\Builder\ImageQueryBuilder;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Crop;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Resize;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Save;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Strip;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
use Abeliani\Blog\Domain\Service\Mailer\MailerInterface;
use Abeliani\Blog\Infrastructure\Service\Mailer;
use Abeliani\Blog\Infrastructure\Service\Twig\Extension;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    Environment::class => function(): Environment {
        $twig = (new Environment(new FilesystemLoader(TEMPLATES_DIR)));
        $twig->addExtension(new Extension\ImageTypeFilter);
        $twig->addExtension(new Extension\TimeToRead);
        $twig->addExtension(new Extension\CsrfToken);
        return $twig;
    },
    PDO::class => function (): PDO {
        return new PDO('mysql:host=db;dbname=bee;charset=utf8mb4', 'root', 'root', [
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
    ConfigDi::CategoryImageBuilder->name => function(): ImageQueryBuilder {
        $upload =  ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR') . DS . 'category';

        $resized = (new ImageQueryBuilder('view'))
            ->append(new Brightness(-30))
            ->append(new Contrast(10))
            ->append(new Discolor(0.105))
            ->lazy(fn (ProcessorContext $c) => Crop::build($c->get('width'), $c->get('height'), $c->get('x'), $c->get('y')), Crop::type())
            ->append(new Resize(new Size(700.0,0)))
            ->append(new Save($upload . DS . 'images/' . date('Y') . DS . uniqid(), IMAGETYPE_WEBP));

        return (new ImageQueryBuilder('original'))
            ->append(new Strip())
            ->append(new Save($upload . DS . 'images/original' . DS . uniqid(), IMAGETYPE_WEBP))
            ->branch($resized);
    },
    ConfigDi::ArticleImageBuilder->name => function(): ImageQueryBuilder {
        $upload =  ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR') . DS . 'article';

        $thumb = (new ImageQueryBuilder('thumb'))
            ->append(new Resize(new Size(200.0, 0)))
            ->append(new Save($upload . DS . date('Y') . DS . uniqid(), IMAGETYPE_WEBP));

        $resized = (new ImageQueryBuilder('view'))
            ->append(new Brightness(-30))
            ->append(new Contrast(10))
            ->append(new Discolor(0.105))
            ->lazy(fn (ProcessorContext $c) => Crop::build($c->get('width'), $c->get('height'), $c->get('x'), $c->get('y')), Crop::type())
            ->append(new Resize(new Size(700.0,0)))
            ->append(new Save($upload . DS . date('Y') . DS . uniqid(), IMAGETYPE_WEBP))
            ->branch($thumb);

        return (new ImageQueryBuilder('original'))
            ->append(new Strip())
            ->append(new Save($upload . DS . 'images/original' . DS . uniqid(), IMAGETYPE_WEBP))
            ->branch($resized);
    },
    MailerInterface::class => function(): Mailer {
        return new Mailer(
            getenv('SMTP_HOST'),
            (int) getenv('SMTP_PORT'),
            getenv('SMTP_USERNAME'),
            getenv('SMTP_PASSWORD'),
        );
    }
];
