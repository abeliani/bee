<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Repository\Article\UpdateRepositoryInterface;

readonly class UpdateRepository implements UpdateRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws \Exception
     */
    public function update(Article $a): void
    {
       try {
        $this->pdo->beginTransaction();

        $sql = 'UPDATE articles SET status = ?, published_at = ?, updated_at = ?, edited_by = ? WHERE id = ?';
        $this->pdo->prepare($sql)->execute([
                $a->getStatus()->value,
                $a->getPublishedAt()->format('Y-m-d H:i:s'),
                $a->getUpdatedAt()->format('Y-m-d H:i:s'),
                $a->getEditedBy(),
                $a->getId(),
            ]);
        
        $sql = <<<SQL
UPDATE article_translations 
    SET title = ?, slug = ?, content = ?, seo_meta = ?, seo_og = ?, media_image = ?, media_image_alt = ?,
        media_video = ?, status = ?
    WHERE article_id = ? AND lang = ?
SQL;

        $this->pdo->prepare($sql)
            ->execute([
                $a->getTitle(),
                $a->getSlug(),
                $a->getContent(),
                $a->getSeoMeta(),
                $a->getSeoOg(),
                $a->getImages(),
                $a->getImageAlt(),
                $a->getVideo(),
                $a->getStatus()->value,
                $a->getId(),
                $a->getLanguage()->value,
            ]);

        $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack() and throw $e;
        }
    }
}