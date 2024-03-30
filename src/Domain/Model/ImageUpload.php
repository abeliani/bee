<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Trait\SurrogateId;

final class ImageUpload
{
    use SurrogateId;

    public function __construct(
        ?int $id,
        private readonly string $alt,
        private readonly int $createdBy,
        private readonly UploadTag $tag,
        private readonly ImageCollection $collection
    ) {
        if ($id) {
            $this->id = $id;
        }
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getTag(): UploadTag
    {
        return $this->tag;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function getCollection(): ImageCollection
    {
        return $this->collection;
    }
}
