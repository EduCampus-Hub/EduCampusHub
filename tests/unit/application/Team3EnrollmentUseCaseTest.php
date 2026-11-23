<?php
//nomi classi test devono finire con TEST !!!!!
namespace tests\unit\application;
use PHPUnit\Framework\TestCase;
 //test da completare perché non ho ancora la classe, ho iniziato la struttura
class Team3EnrollmentUseCaseTest extends TestCase{
    public function testEnrollmentUseCase():void{
$repositoryMock = $this->createMock(JsonEnrollmentRepository::class);
        $repositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $useCase = new EnrollmentUseCase($repositoryMock);
      

    
    }
}