<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter as Mysql;
use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class ArticleTranslations extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('article_translations', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('article_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false, 'null' => false])
            ->addColumn('lang', Adapter::PHINX_TYPE_STRING, ['limit' => 2, 'null' => false])
            ->addColumn('title', Adapter::PHINX_TYPE_STRING, ['limit' => 100])
            ->addColumn('slug', Adapter::PHINX_TYPE_STRING, ['limit' => 100])
            ->addColumn('preview', Adapter::PHINX_TYPE_STRING, ['limit' => Mysql::TEXT_SMALL, 'null' => false])
            ->addColumn('content', Adapter::PHINX_TYPE_TEXT, ['null' => false])
            ->addColumn('seo_meta', Adapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('seo_og', Adapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('media_image_alt', Adapter::PHINX_TYPE_STRING, ['limit' => 150])
            ->addColumn('media_image', Adapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('media_video', Adapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('status', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('view_count', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_BIG])

            ->addIndex(['status'])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['article_id', 'lang'], ['unique' => true])

            ->addForeignKey('article_id', 'articles', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])

            ->create();
    }

    public function down(): void
    {
        $this->table('article_translations')->drop()->save();
    }
}
