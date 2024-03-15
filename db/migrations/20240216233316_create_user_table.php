<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter as Mysql;
use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateUserTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('name', Adapter::PHINX_TYPE_STRING, ['limit' => 50])
            ->addColumn('email', Adapter::PHINX_TYPE_STRING, ['limit' => 50])
            ->addColumn('password', Adapter::PHINX_TYPE_STRING, ['limit' => Mysql::TEXT_SMALL])
            ->addColumn('status', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('role', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_TINY])
            ->addColumn('email_verified_at', Adapter::PHINX_TYPE_DATETIME, ['null' => true])
            ->addColumn('created_at', Adapter::PHINX_TYPE_DATETIME, ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', Adapter::PHINX_TYPE_DATETIME)
            ->addColumn('last_login_at', Adapter::PHINX_TYPE_DATETIME, ['null' => true])
            ->addColumn('login_count', Adapter::PHINX_TYPE_INTEGER, ['default' => 0, 'signed' => false, 'limit' => Mysql::INT_SMALL])
            ->addIndex('email', ['unique' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop()->save();
    }
}
