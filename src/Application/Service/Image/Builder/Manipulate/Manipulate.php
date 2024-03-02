<?php

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\TypeEnum;

abstract class Manipulate implements BuilderActionInterface
{
    public function __construct(private readonly ?string $library = null)
    {
    }

    abstract public function getValue(): mixed;

    public function getType(): string
    {
        return TypeEnum::manipulate->name;
    }

    public function getLibrary(): string
    {
        return $this->library ?? '';
    }

    public function toArray(): array
    {
        return [
            'type' => static::class,
            'value' => $this->getValue()
        ];
    }
}
