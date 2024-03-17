<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\Twig\Extension;

use Abeliani\Blog\Domain\Service\TransliteratorBijective;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class Translator extends AbstractExtension
{
    public function __construct(private readonly TransliteratorBijective $bijective)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('translator_en', [$this, 'toEn']),
        ];
    }

    public function toEn(string $text): string
    {
        return $this->bijective->toEn($text);
    }
}
