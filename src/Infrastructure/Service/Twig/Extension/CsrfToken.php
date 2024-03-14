<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\Twig\Extension;

use Abeliani\Blog\Application\Middleware\CsrfCheckMiddleware;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfToken extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf_token', fn () => $_SESSION[CsrfCheckMiddleware::FIELD_NAME] ?? ''),
        ];
    }
}
