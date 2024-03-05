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
        return self::createFull(
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
            null,
            $status,
            new \DateTimeImmutable()
        );
    }

    /**
     * @throws CategoryException
     */
    public static function createFull(
        ?int $id,
        string $title,
        string $slug,
        string $content,
        array $images,
        string $imageAlt,
        ?string $video,
        array $seoMeta,
        array $seoOg,
        string $language,
        int $createdBy,
        ?int $editedBy,
        CategoryStatus $status,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $publishedAt = null,
        ?\DateTimeImmutable $updatedAt = null,
        ?\DateTimeImmutable $deletedAt = null,
        int $view_count = 0,
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
            $video,
            $seoMeta,
            $seoOg,
            $language,
            $createdBy,
            $editedBy,
            $status,
            $createdAt,
            $publishedAt,
            $updatedAt,
            $deletedAt,
            $view_count
        );
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public static function createFromDb(
        int $id,
        string $title,
        string $slug,
        string $content,
        string $mediaImage,
        string $mediaImageAlt,
        ?string $mediaVideo,
        string $seoMeta,
        string $seoOg,
        string $language,
        int $status,
        int $actorId,
        ?int $editBy,
        string $createdAt,
        string $publishedAt,
        ?string $updatedAt,
    ): Category {
        return self::createFull(
            $id,
            $title,
            $slug,
            $content,
            json_decode($mediaImage, null, 10, JSON_THROW_ON_ERROR),
            $mediaImageAlt,
            $mediaVideo ? json_decode($mediaVideo, true, 10, JSON_THROW_ON_ERROR) : null,
            json_decode($seoMeta, true, 20, JSON_THROW_ON_ERROR),
            json_decode($seoOg, true, 10, JSON_THROW_ON_ERROR),
            $language,
            $actorId,
            $editBy,
            CategoryStatus::tryFrom($status),
            new \DateTimeImmutable($createdAt),
            new \DateTimeImmutable($publishedAt),
            $updatedAt === null ? null : new \DateTimeImmutable($updatedAt),
        );
    }
}
