<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateCategoryTranslateTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('category_translations');
        $table->addColumn('category_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('lang', 'string', ['limit' => 2, 'null' => false])
            ->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('slug', 'string', ['limit' => 100])
            ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('seo_meta', 'json', ['null' => true])
            ->addColumn('seo_og', 'json', ['null' => true])
            ->addColumn('media_image', 'json', ['null' => true])
            ->addColumn('media_video', 'json', ['null' => true])
            ->addColumn('status', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('view_count', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_BIG])

            ->addIndex(['status'])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['category_id', 'lang'], ['unique' => true])

            ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addForeignKey('lang', 'languages', 'language_code', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])

            ->create();
    }
}