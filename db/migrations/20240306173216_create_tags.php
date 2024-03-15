<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface as Adapter;
use Phinx\Migration\AbstractMigration;

final class CreateTags extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('tags', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('name', Adapter::PHINX_TYPE_STRING, ['limit' => 100])
            ->addColumn('frequency', Adapter::PHINX_TYPE_SMALL_INTEGER, ['signed' => false, 'default' => 0])

            ->addIndex(['name'], ['unique' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('tags')->drop()->save();
    }
}
