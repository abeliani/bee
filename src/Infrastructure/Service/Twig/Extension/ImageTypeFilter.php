<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\Twig\Extension;

use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ImageTypeFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_image_type', [$this, 'filterImageType']),
        ];
    }

    public function filterImageType(ImageCollection $images, string $type): ?Image
    {
        return $images->stream()
            ->filter(fn (Image $i) => $i->getType() === $type)
            ->getCollection()
            ->current();
    }
}
