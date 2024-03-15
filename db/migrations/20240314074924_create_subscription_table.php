<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter as Mysql;
use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateSubscriptionTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('subscriptions', ['id' => false, 'primary_key' => ['email']]);
        $table->addColumn('email', Adapter::PHINX_TYPE_STRING, ['null' => false, 'limit' => 50])
            ->addColumn('status', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', Adapter::PHINX_TYPE_DATETIME, ['null' => true])

            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['status'])

            ->create();
    }

    public function down(): void
    {
        $this->table('subscriptions')->drop()->save();
    }
}
