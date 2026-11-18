<?php
namespace App\Domain\Enum;

enum UserRoleEnum {
    case ADMIN;
    case DOCENTE;
    case STUDENTE;
    case ATA;
    case USER;
}