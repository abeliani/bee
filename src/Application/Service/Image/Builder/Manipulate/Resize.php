<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\Args\Size;
use Abeliani\Blog\Application\Service\Image\ManipulateEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD\ResizeGdImage;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick\ResizeImagick;

#[ResizeGdImage]
#[ResizeImagick]
class Resize extends Manipulate
{
    public function __construct(private readonly Size $size, ?string $library = null)
    {
        parent::__construct($library);
    }

    public static function type(): string
    {
        return ManipulateEnum::resize->name;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getValue(): array
    {
        return $this->getSize()->toArray();
    }
}
