<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddFulltextToArticles extends AbstractMigration
{
    public function change(): void
    {
        $this->execute(
            'ALTER TABLE `article_translations` ADD FULLTEXT `idx_article_fulltext` (`title`, `preview`, `content`)'
        );
    }

    public function down(): void
    {
        $this->execute('ALTER TABLE `article_translations` DROP INDEX `idx_article_fulltext`');
    }
}
