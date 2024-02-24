<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLanguageTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('languages', ['id' => false, 'primary_key' => ['language_code']]);
        $table->addColumn('language_code', 'string', ['limit' => 2, 'null' => false])
            ->addColumn('name', 'string', ['limit' => 25, 'null' => false])
            ->create();
    }
}
