<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Factory;

use Abeliani\Blog\Domain\Collection\Concrete;
use Abeliani\Blog\Domain\Entity\ArticleOg;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Entity\SeoMeta;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Exception\ArticleException;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Infrastructure\UI\Form\ArticleForm;

final class ArticleFactory
{
    /**
     * @throws ArticleException
     */
    public static function create(
        int                 $actorId,
        int                 $categoryId,
        string              $title,
        string              $slug,
        string              $preview,
        string              $content,
        string              $tags,
        string              $images,
        string              $imageAlt,
        string              $video,
        array               $seoMeta,
        array               $seoOg,
        Enum\Language       $language,
        Enum\ArticleStatus $status,
        ?int                $id = null,
    ): Article {
        return self::createFull(
            $id,
            $categoryId,
            $title,
            $slug,
            $preview,
            $content,
            $tags,
            $images,
            $imageAlt,
            $video ?: null,
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
     * @throws ArticleException
     */
    public static function createFromForm(
        int $actorId,
        int $authorId,
        \DateTimeImmutable $createdAt,
        int $viewCount,
        ArticleForm $form,
        Concrete\ImageCollection $images
    ): Article {
        return ArticleFactory::createFull(
            $form->getId(),
            $form->getCategoryId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getPreview(),
            $form->getContent(),
            $form->getTags(),
            (string) $images,
            $form->getMedia()->getImageAlt(),
            $form->getMedia()->getVideo(),
            json_encode($form->getSeo()),
            json_encode($form->getOg()),
            $form->getLanguage(),
            $authorId,
            $actorId,
            $form->getStatus(),
            $createdAt,
            new \DateTimeImmutable($form->getPublishedAt()),
            new \DateTimeImmutable(),
            $viewCount
        );
    }

    /**
     * @throws ArticleException
     */
    public static function createFull(
        ?int                $id,
        int                 $categoryId,
        string              $title,
        string              $slug,
        string              $preview,
        string              $content,
        string              $tags,
        string              $images,
        string              $imageAlt,
        ?string             $video,
        string              $seoMeta,
        ?string             $seoOg,
        Enum\Language       $language,
        int                 $createdBy,
        ?int                $editedBy,
        Enum\ArticleStatus $status,
        \DateTimeImmutable  $createdAt,
        ?\DateTimeImmutable $publishedAt = null,
        ?\DateTimeImmutable $updatedAt = null,
        int                 $view_count = 0,
    ): Article {
        if (empty($title)) {
            throw new ArticleException('Title cannot be empty');
        }
        if (empty($slug)) {
            throw new ArticleException('Slug cannot be empty');
        }
        if ($slug === $title) {
            throw new ArticleException('Slug cannot be the same as title');
        }
        if ($preview === $content) {
            throw new ArticleException('Preview cannot be the same as content');
        }

        $tagsParsed = array_map('trim', explode(',', $tags));

        $imagesCollection = new Concrete\ImageCollection();
        foreach (json_decode($images) as $image) {
            $imagesCollection->add(new Image($image->type, $image->url, '', '', ''));
        }

        $seoMeta = json_decode($seoMeta);
        $seoMeta = new SeoMeta($seoMeta->title, $seoMeta->description);

        $seoOg = json_decode($seoOg ?? '');
        $seoOg = new ArticleOg(
            $seoOg->title ?? '',
            Enum\OgType::tryFrom($seoOg->type ?? '') ?? Enum\OgType::Article,
                $seoOg->url ?? '',
                $seoOg->description ?? '',
                $seoOg->site_name ?? '',
            Enum\Locale::tryFrom($seoOg->locale ?? '') ?? Enum\Locale::ru
        );

        return new Article(
            $id,
            $categoryId,
            $title,
            $slug,
            $preview,
            $content,
            $tagsParsed,
            $imagesCollection,
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
            $view_count
        );
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public static function createFromDb(array $data): Article
    {
        return self::createFull(
            $data['id'],
            $data['category_id'],
            $data['title'],
            $data['slug'],
            $data['preview'],
            $data['content'],
            $data['tags'],
            $data['media_image'],
            $data['media_image_alt'],
            $data['media_video'],
            $data['seo_meta'],
            $data['seo_og'],
            Enum\Language::tryFrom($data['lang']),
            $data['author_id'],
            $data['edited_by'],
            Enum\ArticleStatus::tryFrom($data['status']),
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['published_at']),
            $data['updated_at'] === null ? null : new \DateTimeImmutable($data['updated_at']),
            $data['view_count']
        );
    }
}
