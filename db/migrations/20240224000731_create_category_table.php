<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter as Mysql;
use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateCategoryTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('categories', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('status', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', Adapter::PHINX_TYPE_DATETIME)
            ->addColumn('author_id', Adapter::PHINX_TYPE_INTEGER, ['signed' => false])
            ->addColumn('edited_by', Adapter::PHINX_TYPE_INTEGER, ['null' => true, 'signed' => false,])

            ->addIndex(['status'])

            ->addForeignKey('author_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addForeignKey('edited_by', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

            ->create();
    }

    public function down(): void
    {
        $this->table('categories')->drop()->save();
    }
}
