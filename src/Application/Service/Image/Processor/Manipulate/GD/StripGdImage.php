<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Strip;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class StripGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \GdImage $image
     * @param Strip $action
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): mixed
    {
        return null;
    }
}