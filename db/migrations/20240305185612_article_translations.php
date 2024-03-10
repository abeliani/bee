<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class ArticleTranslations extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('article_translations', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('article_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['signed' => false, 'null' => false])
            ->addColumn('lang', MysqlAdapter::PHINX_TYPE_STRING, ['limit' => 2, 'null' => false])
            ->addColumn('title', MysqlAdapter::PHINX_TYPE_STRING, ['limit' => 100])
            ->addColumn('slug', MysqlAdapter::PHINX_TYPE_STRING, ['limit' => 100])
            ->addColumn('preview', MysqlAdapter::PHINX_TYPE_STRING, ['limit' => MysqlAdapter::TEXT_SMALL, 'null' => false])
            ->addColumn('content', MysqlAdapter::PHINX_TYPE_TEXT, ['null' => false])
            ->addColumn('seo_meta', MysqlAdapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('seo_og', MysqlAdapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('media_image_alt', MysqlAdapter::PHINX_TYPE_STRING, ['limit' => 150])
            ->addColumn('media_image', MysqlAdapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('media_video', MysqlAdapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('status', MysqlAdapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('view_count', MysqlAdapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_BIG])

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
