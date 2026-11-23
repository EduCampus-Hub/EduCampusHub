<?php
namespace tests\unit\application;
use PHPUnit\Framework\TestCase;
use App\Application\GetUserUseCase;
use App\Application\DTO;
class GetUserUseCaseTest extends TestCase
{
    public function testGetUserUseCase(): void
    {
        $repositoryMock = $this->createMock(JsonUserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(true);
            $useCase = new GetUserAgendaUseCase($repositoryMock);
    }
      
}