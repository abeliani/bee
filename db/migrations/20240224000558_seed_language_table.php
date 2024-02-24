<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SeedLanguageTable extends AbstractMigration
{
    public function up()
    {
        $rows = [
            ['language_code' => 'en', 'name' => 'English'],
            ['language_code' => 'ru', 'name' => 'Русский'],
        ];

        $this->table('languages')->insert($rows)->saveData();
    }
}
