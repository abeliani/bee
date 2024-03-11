<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Persistence\Mapper;

use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Factory\ArticleFactory;
use Abeliani\Blog\Domain\Mapper\AggregateMapperInterface;
use Abeliani\Blog\Domain\Model\Article;

class ArticleMapper implements AggregateMapperInterface
{
    /**
     * @throws \JsonException
     */
    public function map(array $data): Article
    {
        return ArticleFactory::createFromDb($data);
    }

    public function mapToFormData(Article $article): array
    {
        /** @var Image $image */
        $image = $article->getImages()->stream()
            ->filter(fn(Image $image) => $image->getType() === 'view')
            ->getCollection()
            ->current();

        return [
            'id' => $article->getId(),
            'translate_id' => $article->getTranslateId(),
            'category_id' => $article->getCategoryId(),
            'title' => $article->getTitle(),
            'slug' => $article->getSlug(),
            'seo' => $article->getSeoMeta(),
            'og' => $article->getSeoOg(),
            'imageTitle' => $article->getImageAlt(),
            'image_loaded' => $image?->getUrl(),
            'video' => $article->getVideo(),
            'preview' => $article->getPreview(),
            'content' => $article->getContent(),
            'tags' => implode(',', $article->getTags()),
            'status' => $article->getStatus()->value,
            'publish_at' => $article->getPublishedAt()->format('m/d/Y H:i:s'),
            'language' => $article->getLanguage()->value,
        ];
    }

    /**
     * @throws \JsonException
     */
    public function mapMany(array $data): array
    {
        return array_map( fn (array $article) => $this->map($article), $data);
    }
}