<?php 

namespace App\Application\DTO;

class UserAgendaDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $agendaId,
        public readonly array $listaCorsi = [],
    ) {}
}