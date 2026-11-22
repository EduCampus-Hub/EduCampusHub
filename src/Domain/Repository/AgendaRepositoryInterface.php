<?php

namespace App\Domain\Repository;

use App\Domain\Model\Agenda;

interface AgendaRepositoryInterface
{
    public function findByUser(string $userId): ?Agenda;

    public function save(string $userId, Agenda $agenda): bool;
}
