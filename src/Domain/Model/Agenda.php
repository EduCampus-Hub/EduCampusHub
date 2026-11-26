<?php

namespace App\Domain\Model;

class Agenda
{
    private function __construct(
        public readonly array $listaCorsi
    ) {}


    public static function create(array $listaCorsi): self
    {
        return new self($listaCorsi);
    }


    public static function restore(array $listaCorsi): self
    {
        return new self($listaCorsi);
    }
}

