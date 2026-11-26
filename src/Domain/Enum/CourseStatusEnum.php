<?php
namespace App\Domain\Enum;

enum CourseStatusEnum: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
   
}