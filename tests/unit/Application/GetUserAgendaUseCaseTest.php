<?php


namespace Tests\Unit\Application;


use App\Application\DTO\UserAgendaDTO;
use App\Application\UseCase\GetUserAgendaUseCase;
use App\Domain\Model\Agenda;
use App\Domain\Repository\AgendaRepositoryInterface;
use PHPUnit\Framework\TestCase;


class GetUserAgendaUseCaseTest extends TestCase
{
    public function test_executes_use_case_and_returns_user_agenda_when_found(): void
    {
        // Mock dell'interfaccia Repository
        $repositoryMock = $this->createMock(AgendaRepositoryInterface::class);


        $userId = 'user-123';
        $corsi = ['Matematica', 'Informatica'];
        $agenda = Agenda::create($corsi);


        // Ci aspettiamo che findByUser venga chiamato 1 volta con $userId e restituisca l'agenda
        $repositoryMock->expects($this->once())
            ->method('findByUser')
            ->with($userId)
            ->willReturn($agenda);


        $useCase = new GetUserAgendaUseCase($repositoryMock);


        $result = $useCase->execute($userId);


        $this->assertInstanceOf(UserAgendaDTO::class, $result);
        $this->assertSame($userId, $result->userId);
        $this->assertSame($corsi, $result->listaCorsi);
    }


    public function test_executes_use_case_and_returns_empty_agenda_when_not_found(): void
    {
        // Mock dell'interfaccia Repository
        $repositoryMock = $this->createMock(AgendaRepositoryInterface::class);


        $userId = 'user-404';


        // Ci aspettiamo che findByUser venga chiamato 1 volta e restituisca null
        $repositoryMock->expects($this->once())
            ->method('findByUser')
            ->with($userId)
            ->willReturn(null);


        $useCase = new GetUserAgendaUseCase($repositoryMock);


        $result = $useCase->execute($userId);


        $this->assertInstanceOf(UserAgendaDTO::class, $result);
        $this->assertSame($userId, $result->userId);
        $this->assertSame([], $result->listaCorsi);
    }
}
