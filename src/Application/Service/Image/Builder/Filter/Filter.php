<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\TypeEnum;

abstract readonly class Filter implements BuilderActionInterface
{
    abstract public function getValue(): mixed;
    abstract public static function type(): string;

    public function getType(): string
    {
        return TypeEnum::filter->name;
    }

    public function getLibrary(): string
    {
        return '';
    }

    public function toArray(): array
    {
        return [static::type() => $this->getValue()];
    }
}
