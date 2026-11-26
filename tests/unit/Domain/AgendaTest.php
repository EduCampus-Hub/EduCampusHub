<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Model\Agenda;

class AgendaTest extends TestCase
{
    public function test_can_create_new_agenda(): void
    {
        $agenda = Agenda::create(
            listaCorsi: ['corsi 1', 'corsi 3']
        );

        $this->assertEquals(['corsi 1', 'corsi 3'], $agenda->listaCorsi);
    }

    public function test_can_restore_existing_agenda(): void
    {
        $agenda = Agenda::restore(
            listaCorsi: ['corsi 1', 'corsi 3']
        );

        $this->assertEquals(['corsi 1', 'corsi 3'], $agenda->listaCorsi);
    }
}