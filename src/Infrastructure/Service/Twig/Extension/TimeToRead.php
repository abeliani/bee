<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeToRead extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_to_read', [$this, 'calculate']),
        ];
    }

    public function calculate(string $text): int
    {
        return (int) round(str_word_count($text) / 200);
    }
}
