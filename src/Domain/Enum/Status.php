<?php
namespace App\Domain\Enum;
enum Status {
    case Pending;
    case Confirmed;
    case Negated;
}