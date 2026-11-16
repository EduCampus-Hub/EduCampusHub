<?php
namespace App\Domain\Enum;

enum TargetAudienceEnum: string
{
    case STUDENTS = 'students';
    case TEACHERS = 'teachers';
    case ATC = 'atc';
    case ALL = 'all';
}