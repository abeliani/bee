<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Repository\Tag;

/**
 * Info repo to show using triggers
 * @see AddIncrementTrigger
 * @see AddDecrementTrigger
 */
final readonly class TagFrequencyRepository
{
    public const MYSQL_INCREMENT_TAG_FREQUENCY_TRIGGER = <<<SQL
CREATE TRIGGER after_article_tag_insert
    AFTER INSERT ON article_tags
    FOR EACH ROW
    BEGIN
        UPDATE tags SET frequency = frequency + 1 WHERE id = NEW.tag_id;
    END;
SQL;

    public const MYSQL_DECREMENT_TAG_FREQUENCY_TRIGGER = <<<SQL
CREATE TRIGGER after_article_tag_delete
    AFTER DELETE ON article_tags
    FOR EACH ROW
    BEGIN
        UPDATE tags SET frequency = frequency - 1 WHERE id = OLD.tag_id;
    END;
SQL;

    public const MYSQL_DROP_INCREMENT_TRIGGER = 'DROP TRIGGER IF EXISTS after_article_tag_insert;';

    public const MYSQL_DROP_DECREMENT_TRIGGER = 'DROP TRIGGER IF EXISTS after_article_tag_delete;';
}