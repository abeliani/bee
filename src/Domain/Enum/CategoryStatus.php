<?php

namespace Abeliani\Blog\Domain\Enum;

enum CategoryStatus: int
{
    case Draft = 0;
    case Published = 1;
    case Archived = 2;
    case Deleted = 3;
}
