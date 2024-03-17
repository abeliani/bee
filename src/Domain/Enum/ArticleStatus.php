<?php

namespace Abeliani\Blog\Domain\Enum;

enum ArticleStatus: int
{
    case Draft = 0;
    case Published = 1;
    case Outdated = 2;
    case Archived = 3;
    case Deleted = 4;
}
