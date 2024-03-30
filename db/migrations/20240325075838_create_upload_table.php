<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateUploadTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('uploads', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('tag', Adapter::PHINX_TYPE_STRING, ['null' => false, 'limit' => 25])
            ->addColumn('title', Adapter::PHINX_TYPE_STRING, ['null' => false, 'limit' => 100])
            ->addColumn('files', Adapter::PHINX_TYPE_JSON, ['null' => true])
            ->addColumn('created_by', Adapter::PHINX_TYPE_INTEGER, ['signed' => false])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])

            ->addIndex(['tag'])

            ->addForeignKey('created_by', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

            ->create();
    }
}
