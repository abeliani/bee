<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Enum\ConfigDi;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Application\Service\Subscription\SubscriptionService;
use Abeliani\Blog\Application\Service\UserRegistration\UserRegistrationService;
use Abeliani\Blog\Domain\Factory\UserFactory;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category\CreateCategoryRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Category\UpdateCategoryRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Subscription\CreateRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Subscription\ReadRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Redirector;
use Abeliani\Blog\Domain\Service\Mailer\MailerInterface;
use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;
use Abeliani\Blog\Domain\Service\TransliteratorBijective;
use Abeliani\Blog\Infrastructure\Service;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\RequestValidatorService;
use DI\Container;
use Symfony\Component\Validator\Validation;
use Twig\Environment;

return [
    UserFactory::class => function(Container $c): UserFactory {
        return new UserFactory($c->get(PasswordHasherInterface::class));
    },
    UserRegistrationService::class => function(Container $c): UserRegistrationService {
        return new UserRegistrationService(
            $c->get(CreateUserRepositoryInterface::class),
            $c->get(UserFactory::class),
        );
    },
    Service\JWTAuthentication::class => function(): Service\JWTAuthentication {
        return new Service\JWTAuthentication(
            getenv('JWT_SECRET'),
            getenv('APP_DOMAIN'),
            getenv('APP_NAME'),
            '+24 hour',
            'auth_token'
        );
    },
    PasswordHasherInterface::class => function(): PasswordHasherInterface {
        return new Service\PasswordHasher();
    },
    Service\Login::class => function(Container $c): Service\Login {
        return new Service\Login(
            $c->get(Service\JWTAuthentication::class),
            $c->get(PasswordHasherInterface::class),
            $c->get(ReadUserRepositoryInterface::class)
        );
    },
    RequestValidatorService::class => function(): RequestValidatorService {
        return new RequestValidatorService(
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator(),
        );
    },
    FormService::class => function(Container $c): FormService {
        return new FormService(new Service\Hydrator(), $c->get(RequestValidatorService::class));
    },
    ConfigDi::CategoryImageProcessor->name => function(Container $c): ImageQueryProcessor {
        return new ImageQueryProcessor(
            $c->get(ConfigDi::CategoryImageBuilder->name),
            \Imagick::class,
            \GdImage::class,
        );
    },
    ConfigDi::ArticleImageProcessor->name => function(Container $c): ImageQueryProcessor {
        return new ImageQueryProcessor(
            $c->get(ConfigDi::ArticleImageBuilder->name),
            \Imagick::class,
            \GdImage::class,
        );
    },
    ConfigDi::UploadImageProcessor->name => function(Container $c): ImageQueryProcessor {
        return new ImageQueryProcessor(
            $c->get(ConfigDi::UploadImageBuilder->name),
            \Imagick::class,
            \GdImage::class,
        );
    },
    Service\CategoryService::class => function(Container $c):Service\CategoryService {
        return new Service\CategoryService(
            $c->get(CreateCategoryRepositoryInterface::class),
            $c->get(UpdateCategoryRepositoryInterface::class),
            $c->get(ConfigDi::CategoryImageProcessor->name),
            new SavePathPremakeProcessor($c->get(ConfigDi::CategoryImageBuilder->name)),
            ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR')
        );
    },
    Service\ArticleService::class => function(Container $c): Service\ArticleService {
        return new Service\ArticleService(
            $c->get(Article\CreateRepositoryInterface::class),
            $c->get(Article\UpdateRepositoryInterface::class),
            $c->get(ConfigDi::ArticleImageProcessor->name),
            new SavePathPremakeProcessor($c->get(ConfigDi::ArticleImageBuilder->name)),
            $c->get(Service\Redirector::class),
            ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR') . DS . 'article',
            getenv('APP_HOST'),
        );
    },
    \Abeliani\Blog\Application\Service\Upload\UploadService::class => function (Container $c): \Abeliani\Blog\Application\Service\Upload\UploadService {
        return new \Abeliani\Blog\Application\Service\Upload\UploadService(
            $c->get(ConfigDi::UploadImageProcessor->name),
            new SavePathPremakeProcessor($c->get(ConfigDi::UploadImageBuilder->name)),
            $c->get(\Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface::class),
            ROOT_DIR . DS . getenv('FILE_UPLOAD_DIR'),
        );
    },
    SubscriptionService::class => function(Container $c): SubscriptionService {
        return new SubscriptionService(
            getenv('MAILER_TOKEN_SECRET'),
            getenv('APP_DOMAIN'),
            getenv('APP_NAME'),
            '+3 hour',
            $c->get(Environment::class),
            $c->get(MailerInterface::class),
            $c->get(CreateRepositoryInterface::class),
            $c->get(ReadRepositoryInterface::class),
        );
    },
    TransliteratorBijective::class => function(Container $c): TransliteratorBijective {
        return new TransliteratorBijective();
    },
    Service\Redirector::class => function(Container $c): Service\Redirector {
        return new Service\Redirector($c->get(Redirector\RepositoryInterface::class));
    },
];
