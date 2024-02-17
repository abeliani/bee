<?php

namespace Abeliani\Blog\Domain\Enum;

enum UserStatus: int
{
    case InActive = 0;
    case Active = 1;
    case Blocked = 2;
    case Deleted = 3;
}
