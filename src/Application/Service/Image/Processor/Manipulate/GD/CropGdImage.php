<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Crop;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CropGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param Crop $action
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \GdImage
    {
        $image = imagecrop($image, [
            'width' => (int)$action->getSize()->getWidth(),
            'height' => (int)$action->getSize()->getHeight(),
            'x' => (int)$action->getCoords()->getX(),
            'y' => (int)$action->getCoords()->getY(),
        ]);

        if (!$image) {
            throw new \RuntimeException('Failed to crop image');
        }

        return $image;
    }
}