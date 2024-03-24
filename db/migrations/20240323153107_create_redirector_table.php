<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

use Phinx\Db\Adapter\MysqlAdapter as Mysql;
use Phinx\Db\Adapter\AdapterInterface as Adapter;

final class CreateRedirectorTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('redirector', ['id' => false, 'primary_key' => ['hash']]);
        $table->addColumn('hash', Adapter::PHINX_TYPE_STRING, ['null' => false, 'limit' => 8])
            ->addColumn('path', Adapter::PHINX_TYPE_STRING, ['null' => false, 'limit' => Mysql::TEXT_SMALL])
            ->addColumn('protocol', Adapter::PHINX_TYPE_INTEGER, ['null' => false, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('fast', Adapter::PHINX_TYPE_BOOLEAN, ['default' => false])
            ->addColumn('view_count', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_BIG])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])

            ->addIndex(['hash'], ['unique' => true])

            ->create();
    }
}
