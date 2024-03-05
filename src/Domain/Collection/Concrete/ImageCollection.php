<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Collection\Concrete;

use Abeliani\Blog\Domain\Collection\Collection;
use Abeliani\Blog\Domain\Entity\Image;

class ImageCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Image::class);
    }
}