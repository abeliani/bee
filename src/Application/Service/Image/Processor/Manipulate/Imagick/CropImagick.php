<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Crop;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CropImagick implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \Imagick $image
     * @param Crop $action
     * @throws \ImagickException
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \Imagick
    {
        $result = $image->cropImage(
            (int)$action->getSize()->getWidth(),
            (int)$action->getSize()->getHeight(),
            (int)$action->getCoords()->getX(),
            (int)$action->getCoords()->getY(),
        );

        if (!$result) {
            throw new \RuntimeException('Failed to crop image');
        }

        return $image;
    }
}