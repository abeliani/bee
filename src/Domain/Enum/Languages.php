<?php
declare(strict_types=1);

namespace Abeliani\Blog\Domain\Enum;

enum Languages: string
{
    case Russian = 'ru';
    case English = 'en';
}