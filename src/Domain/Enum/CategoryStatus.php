<?php

namespace Abeliani\Blog\Domain\Enum;

enum CategoryStatus: int
{
    case Draft = 0;
    case Published = 1;
}
