<?php

namespace Abeliani\Blog\Domain\Repository\Upload;

use Abeliani\Blog\Domain\Collection\Concrete\ImageUploadCollection;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Model\ImageUpload;

interface ImageRepositoryInterface
{
    public function findAll(?UploadTag $tag = null): ImageUploadCollection;
    public function find(int $id): ?ImageUpload;
    public function save(ImageUpload $iu): bool;
}