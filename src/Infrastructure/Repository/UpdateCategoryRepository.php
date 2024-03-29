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

        $this->pdo->prepare('UPDATE categories SET status = ?, updated_at = ?, edited_by = ? WHERE id = ?')
            ->execute([
                $c->getStatus()->value,
                $c->getUpdatedAt()->format('Y-m-d H:i:s'),
                $c->getEditedBy(),
                $c->getId(),
            ]);
        
        $sql = <<<SQL
UPDATE category_translations 
    SET title = ?, slug = ?, content = ?, seo_meta = ?, seo_og = ?, media_image = ?, media_image_alt = ?, status = ?
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
                $c->getStatus()->value,
                $c->getId(),
                $c->getLanguage()->value,
            ]);

        $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack() and throw $e;
        }
    }
}