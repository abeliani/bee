<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Articles extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('articles', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('status', Adapter::PHINX_TYPE_TINY_INTEGER, ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('category_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false, 'null' => false])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('published_at', Adapter::PHINX_TYPE_DATETIME)
            ->addColumn('updated_at', Adapter::PHINX_TYPE_DATETIME)
            ->addColumn('author_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false])
            ->addColumn('edited_by', Adapter::PHINX_TYPE_INTEGER, ['null' => true, 'signed' => false,])

            ->addIndex(['category_id'])
            ->addIndex(['status', 'published_at'])

            ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addForeignKey('author_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addForeignKey('edited_by', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

            ->create();
    }

    public function down(): void
    {
        $this->table('articles')->drop()->save();
    }
}
