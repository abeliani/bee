<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AlterPreviewColumnInArticleTranslations extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('article_translations');
        $users->changeColumn('preview', AdapterInterface::PHINX_TYPE_STRING, ['limit' => 1000])
            ->save();
    }

    public function down()
    {
        $users = $this->table('article_translations');
        $users->changeColumn('preview', AdapterInterface::PHINX_TYPE_STRING, ['limit' => 255])
            ->save();
    }
}
