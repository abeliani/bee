<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Factory;

use Abeliani\Blog\Domain\Collection\Concrete;
use Abeliani\Blog\Domain\Entity\CategoryOg;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Entity\SeoMeta;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Infrastructure\UI\Form\CategoryForm;

final class CategoryFactory
{
    /**
     * @throws CategoryException
     */
    public static function create(
        int                 $actorId,
        string              $title,
        string              $slug,
        string              $content,
        string              $images,
        string              $imageAlt,
        array               $seoMeta,
        array               $seoOg,
        Enum\Language       $language,
        Enum\CategoryStatus $status,
        ?int                $id = null,
    ): Category
    {
        return self::createFull(
            $id,
            $title,
            $slug,
            $content,
            $images,
            $imageAlt,
            json_encode($seoMeta),
            json_encode($seoOg),
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
    public static function createFromForm(
        int $actorId,
        int $authorId,
        \DateTimeImmutable $createdAt,
        CategoryForm $form,
        Concrete\ImageCollection $images
    ): Category {
        return CategoryFactory::createFull(
            $form->getId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getContent(),
            (string) $images,
            $form->getMedia()->getImageAlt(),
            json_encode($form->getSeo()),
            json_encode($form->getOg()),
            $form->getLanguage(),
            $authorId,
            $actorId,
            $form->getStatus(),
            $createdAt,
            new \DateTimeImmutable(),
        );
    }

    /**
     * @throws CategoryException
     */
    public static function createFull(
        ?int                $id,
        string              $title,
        string              $slug,
        string              $content,
        string              $images,
        string              $imageAlt,
        string              $seoMeta,
        ?string             $seoOg,
        Enum\Language       $language,
        int                 $createdBy,
        ?int                $editedBy,
        Enum\CategoryStatus $status,
        \DateTimeImmutable  $createdAt,
        ?\DateTimeImmutable $updatedAt = null,
    ): Category
    {
        if (empty($title)) {
            throw new CategoryException('Title cannot be empty');
        }
        if (empty($slug)) {
            throw new CategoryException('Slug cannot be empty');
        }
        if ($slug === $title) {
            throw new CategoryException('Slug cannot be the same as title');
        }

        $imagesCollection = new Concrete\ImageCollection();
        foreach (json_decode($images) as $image) {
            $imagesCollection->add(new Image($image->type, $image->url, '', '', ''));
        }

        $seoMeta = json_decode($seoMeta);
        $seoMeta = new SeoMeta($seoMeta->title, $seoMeta->description);

        $seoOg = json_decode($seoOg ?? '');
        $seoOg = new CategoryOg(
            $seoOg->title ?? '',
            Enum\OgType::tryFrom($seoOg->type ?? '') ?? Enum\OgType::Article,
                $seoOg->url ?? '',
                $seoOg->description ?? '',
                $seoOg->site_name ?? '',
            Enum\Locale::tryFrom($seoOg->locale ?? '') ?? Enum\Locale::ru
        );

        return new Category(
            $id,
            $title,
            $slug,
            $content,
            $imagesCollection,
            $imageAlt,
            $seoMeta,
            $seoOg,
            $language,
            $createdBy,
            $editedBy,
            $status,
            $createdAt,
            $updatedAt,
        );
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public static function createFromDb(array $data): Category
    {
        return self::createFull(
            $data['id'],
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['media_image'],
            $data['media_image_alt'],
            $data['seo_meta'],
            $data['seo_og'],
            Enum\Language::tryFrom($data['lang']),
            $data['author_id'],
            $data['edited_by'],
            Enum\CategoryStatus::tryFrom($data['status']),
            new \DateTimeImmutable($data['created_at']),
            $data['updated_at'] === null ? null : new \DateTimeImmutable($data['updated_at']),
        );
    }
}
