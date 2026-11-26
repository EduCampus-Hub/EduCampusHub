<?php

namespace app\Domain\Enum;

enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case DOCENTE = 'DOCENTE';
    case STUDENTE = 'STUDENTE';
    case ATA = 'ATA';
}