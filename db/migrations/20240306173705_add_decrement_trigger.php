<?php

declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Tag\TagFrequencyRepository;
use Phinx\Migration\AbstractMigration;

final class AddDecrementTrigger extends AbstractMigration
{
    public function change(): void
    {
        $this->execute(TagFrequencyRepository::MYSQL_DECREMENT_TAG_FREQUENCY_TRIGGER);
    }

    public function down(): void
    {
        $this->execute(TagFrequencyRepository::MYSQL_DROP_DECREMENT_TRIGGER);
    }
}
