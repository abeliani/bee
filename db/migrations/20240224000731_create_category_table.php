<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateCategoryTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('categories', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('status', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('published_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addColumn('author_id', 'integer', ['signed' => false])
            ->addColumn('edited_by', 'integer', ['null' => true, 'signed' => false,])

            ->addIndex(['status', 'published_at'])

            ->addForeignKey('author_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addForeignKey('edited_by', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

            ->create();
    }

    public function down(): void
    {
        $this->table('categories')->drop()->save();
    }
}
