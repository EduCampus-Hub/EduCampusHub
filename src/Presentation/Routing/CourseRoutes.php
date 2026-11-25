<?php

namespace App\Presentation\Routing;

final class CourseRoutes
{
    public const INDEX = '/corsi';
    public const CREATE = '/corsi/nuovo';
    public const EDIT = '/corsi/{id}/modifica';

    public static function editPath(string $id): string
    {
        return str_replace('{id}', rawurlencode($id), self::EDIT);
    }
}
