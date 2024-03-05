<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository;

use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Domain\Repository\Category\CategoryCollection;
use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;

readonly class ReadCategoryRepository implements ReadCategoryRepositoryInterface
{
    private const BASE_SQL = <<<SQL
        SELECT c.id, c.created_at, c.published_at, c.updated_at, c.author_id, c.edited_by,
               ct.lang, ct.title, ct.slug, ct.content, ct.seo_meta, ct.seo_og, ct.media_image,
               ct.media_image_alt, ct.media_image, ct.media_video, ct.status, ct.view_count
        FROM categories c
        INNER JOIN category_translations ct ON c.id = ct.category_id AND ct.lang='ru'
SQL;

    public function __construct(private \PDO $pdo, private CategoryMapper $mapper)
    {
    }

    /**
     * @throws \JsonException
     */
    public function find(int $id, int $creatorId): ?Category
    {
        $stmt = $this->pdo->prepare(sprintf('%s WHERE c.id = ? AND c.author_id = ? LIMIT 1', self::BASE_SQL));
        $stmt->execute([$id, $creatorId]);

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }

    /**
     * @throws \JsonException
     */
    public function findAll(): CategoryCollection
    {
        $stmt = $this->pdo->prepare(self::BASE_SQL);
        $stmt->execute();
        $collection = new CategoryCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }
}
