<?php

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\ManipulateEnum;

class Convert extends Manipulate
{
    public function __construct(private readonly int $mimeType, ?string $library = null)
    {
        parent::__construct($library);
    }

    public function getMimeType(): int
    {
        return $this->mimeType;
    }

    public static function type(): string
    {
        return ManipulateEnum::convert->name;
    }

    public function getValue(): int
    {
        return $this->getMimeType();
    }
}
