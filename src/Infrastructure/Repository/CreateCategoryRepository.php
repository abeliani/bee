<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository;

use Abeliani\Blog\Domain\Exception\DomainException;
use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Domain\Repository\Category\CreateCategoryRepositoryInterface;

readonly class CreateCategoryRepository implements CreateCategoryRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws DomainException
     */
    public function create(Category $c): void
    {
       try {
        $this->pdo->beginTransaction();

        $sql = 'INSERT INTO categories (status, published_at, author_id) VALUES (?, ?, ?)';
        $this->pdo->prepare($sql)
            ->execute([$c->getStatus()->value, $c->getPublishedAt()->format('Y-m-d H:i:s'), $c->getCreatedBy()]);
        
        $c->setId((int) $this->pdo->lastInsertId());

        $sql = <<<SQL
INSERT INTO category_translations 
    (category_id, lang, title, slug, content, seo_meta, seo_og, media_image, media_image_alt, media_video, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
SQL;

        $this->pdo->prepare($sql)
            ->execute([
                $c->getId(),
                $c->getLanguage()->value,
                $c->getTitle(),
                $c->getSlug(),
                $c->getContent(),
                $c->getSeoMeta(),
                $c->getSeoOg(),
                $c->getImages(),
                $c->getImageAlt(),
                $c->getVideo(),
                $c->getStatus()->value,
            ]);

        $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack() and throw $e;
        }
    }
}