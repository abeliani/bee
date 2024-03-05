<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Persistence\Mapper;

use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Factory\CategoryFactory;
use Abeliani\Blog\Domain\Mapper\AggregateMapperInterface;
use Abeliani\Blog\Domain\Model\Category;

class CategoryMapper implements AggregateMapperInterface
{
    /**
     * @throws \JsonException
     */
    public function map(array $data): Category
    {
        return CategoryFactory::createFromDb($data);
    }

    public function mapToFormData(Category $category): array
    {
        /** @var Image $image */
        $image = $category->getImages()->stream()
            ->filter(fn(Image $image) => $image->getType() === 'view')
            ->getCollection()
            ->current();

        return [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
            'slug' => $category->getSlug(),
            'seo' => $category->getSeoMeta(),
            'og' => $category->getSeoOg(),
            'imageTitle' => $category->getImageAlt(),
            'image_loaded' => $image?->getUrl(),
            'video' => $category->getVideo(),
            'content' => $category->getContent(),
            'status' => $category->getStatus()->value,
            'publish_at' => $category->getPublishedAt()->format('m/d/Y H:i:s'),
            'language' => $category->getLanguage()->value,
        ];
    }

    /**
     * @throws \JsonException
     */
    public function mapMany(array $data): array
    {
        return array_map( fn (array $category) => $this->map($category), $data);
    }
}