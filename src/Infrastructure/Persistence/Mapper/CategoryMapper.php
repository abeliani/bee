<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Persistence\Mapper;

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
        return CategoryFactory::createFromDb(
            $data['id'],
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['media_image'],
            $data['media_image_alt'],
            $data['media_video'],
            $data['seo_meta'],
            $data['seo_og'],
            $data['lang'],
            $data['status'],
            $data['author_id'],
            $data['edited_by'],
            $data['created_at'],
            $data['published_at'],
            $data['updated_at'],
        );
    }

    /**
     * @throws \JsonException
     */
    public function mapMany(array $data): array
    {
        return array_map( fn (array $category) => $this->map($category), $data);
    }
}