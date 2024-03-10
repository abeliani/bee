<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\Args\Coords;
use Abeliani\Blog\Application\Service\Image\Args\Size;
use Abeliani\Blog\Application\Service\Image\ManipulateEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD\CropGdImage;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick\CropImagick;

#[CropGdImage]
#[CropImagick]
class Crop extends Manipulate
{
    public function __construct(
        private readonly Size $size,
        private readonly Coords $coords,
        ?string $library = null,
    ) {
        parent::__construct($library);
    }

    public static function build(float $width, float $height, float $coordX, float $coordY): self
    {
        return new Crop(new Size($width, $height), new Coords($coordX, $coordY));

    }

    public static function type(): string
    {
        return ManipulateEnum::crop->name;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getCoords(): Coords
    {
        return $this->coords;
    }

    public function getValue(): array
    {
        return array_merge(
            $this->getSize()->toArray(),
            $this->getCoords()->toArray()
        );
    }
}