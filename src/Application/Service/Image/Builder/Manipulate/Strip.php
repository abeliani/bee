<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\ManipulateEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD\StripGdImage;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick\StripImagick;

#[StripGdImage]
#[StripImagick]
class Strip extends Manipulate
{
    public function __construct(private readonly array $profiles = [], ?string $library = null)
    {
        parent::__construct($library);
    }

    public static function getName(): string
    {
        return ManipulateEnum::strip->name;
    }

    public function getProfiles(): array
    {
        return $this->profiles;
    }

    public function getValue(): array
    {
        return $this->getProfiles();
    }
}