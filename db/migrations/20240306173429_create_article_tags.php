<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateArticleTags extends AbstractMigration
{
    public function change(): void
    {
        $articleTags = $this->table('article_tags', ['id' => false, 'primary_key' => ['article_translate_id', 'tag_id']]);
        $articleTags
            ->addColumn('article_translate_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false, 'null' => false])
            ->addColumn('tag_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false, 'null' => false])

            ->addForeignKey('article_translate_id', 'article_translations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('tag_id', 'tags', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])

            ->create();
    }
}
