<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Strip;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class StripImagick implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \Imagick $image
     * @param Strip $action
     * @throws \ImagickException
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \Imagick
    {
        $profiles = $image->getImageProfiles();
        if (!empty($profiles['icc'])) {
            $icc = $profiles['icc'];
        }

        if (!$image->stripImage()) {
            throw new \RuntimeException('Failed to strip image');
        }

        if (!empty($icc)) {
            $image->profileImage('icc', $icc);
        }

        return $image;
    }
}