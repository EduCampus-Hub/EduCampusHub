<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Model\Agenda;

class AgendaTest extends TestCase
{
     public function test_can_create_new_Agenda(): void 
    {
        $agenda = Agenda::Create
        (
            listaCorsi: ['corsi 1', 'corsi 3']
        );

        $this->AssertEquals(['corsi 1', 'corsi 3'], $agenda->listaCorsi);
    }
    public function test_can_restore_existem_Agenda(): void 
    {
        $agenda = Agenda::Restore
        (
            listaCorsi: ['corsi 1', 'corsi 3']
        );

        $this->AssertEquals(['corsi 1', 'corsi 3'], $agenda->listaCorsi);
    }
}