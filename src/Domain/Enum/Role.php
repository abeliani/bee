<?php

namespace Abeliani\Blog\Domain\Enum;

enum Role: int
{
    case Visitor = 0;
    case Registered = 1;
    case Editor = 2;
    case Manager = 3;
    case Admin = 4;
    case Root = 5;
}
