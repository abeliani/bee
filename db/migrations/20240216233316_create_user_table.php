<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateUserTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('status', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('role', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('email_verified_at', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime')
            ->addColumn('last_login_at', 'datetime', ['null' => true])
            ->addColumn('login_count', 'integer', ['default' => 0, 'signed' => false, 'limit' => MysqlAdapter::INT_SMALL])
            ->addIndex('email', ['unique' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop()->save();
    }
}
