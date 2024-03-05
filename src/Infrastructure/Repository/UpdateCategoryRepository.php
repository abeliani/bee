<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository;

use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Domain\Repository\Category\UpdateCategoryRepositoryInterface;

readonly class UpdateCategoryRepository implements UpdateCategoryRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws \Exception
     */
    public function update(Category $c): void
    {
       try {
        $this->pdo->beginTransaction();

        $sql = 'UPDATE categories SET status = ?, published_at = ?, updated_at = ?, edited_by = ? WHERE id = ?';
        $this->pdo->prepare($sql)->execute([
                $c->getStatus()->value,
                $c->getPublishedAt()->format('Y-m-d H:i:s'),
                $c->getUpdatedAt()->format('Y-m-d H:i:s'),
                $c->getEditedBy(),
                $c->getId(),
            ]);
        
        $sql = <<<SQL
UPDATE category_translations 
    SET title = ?, slug = ?, content = ?, seo_meta = ?, seo_og = ?, media_image = ?, media_image_alt = ?,
        media_video = ?, status = ?, view_count = ?
    WHERE category_id = ? AND lang = ?
SQL;

        $this->pdo->prepare($sql)
            ->execute([
                $c->getTitle(),
                $c->getSlug(),
                $c->getContent(),
                $c->getSeoMeta(),
                $c->getSeoOg(),
                $c->getImages(),
                $c->getImageAlt(),
                $c->getVideo(),
                $c->getStatus()->value,
                $c->getViewCount(),
                $c->getId(),
                $c->getLanguage()->value,
            ]);

        $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack() and throw $e;
        }
    }
}