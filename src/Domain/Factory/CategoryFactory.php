<?php

declare(strict_types=1);


namespace Abeliani\Blog\Domain\Factory;

use Abeliani\Blog\Domain\Enum\CategoryStatus;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Model\Category;

final class CategoryFactory
{
    /**
     * @throws CategoryException
     */
    public static function create(
        int $actorId,
        string $title,
        string $slug,
        string $content,
        array $images,
        string $imageAlt,
        string $video,
        array $seoMeta,
        array $seoOg,
        string $language,
        CategoryStatus $status,
        ?int $id = null,
    ): Category {
        if (empty($title)) {
            throw new CategoryException('Title cannot be empty');
        }
        if (empty($slug)) {
            throw new CategoryException('Slug cannot be empty');
        }
        if ($slug === $title) {
            throw new CategoryException('Slug cannot be the same as title');
        }
        
        return new Category(
            $id,
            $title,
            $slug,
            $content,
            $images,
            $imageAlt,
            $video ?: null,
            $seoMeta,
            $seoOg,
            $language,
            $actorId,
            $status->value,
            new \DateTimeImmutable()
        );
    }
}
