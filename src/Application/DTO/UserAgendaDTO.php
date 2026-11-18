<?php 

namespace App\Application\DTO;

readonly class UserAgendaDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly array $listaCorsi = [],
    ) {}
}