<?php


namespace Tests\Unit\Application;


use App\Application\UseCase\GenerateCertificateUseCase;
use App\Domain\Model\Certificate;
use App\Domain\Repository\CertificateRepositoryInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;


class GenerateCertificateUseCaseTest extends TestCase
{
    public function test_executes_use_case_and_saves_certificate(): void
    {
        // Mock dell'interfaccia Repository
        $repositoryMock = $this->createMock(CertificateRepositoryInterface::class);


        $enrollmentId = 'enr-100';
        $userId = 'user-42';
        $coursedId = 'course-99';


        // Ci aspettiamo che findByEnrollment venga chiamato 1 volta e non trovi nulla (null)
        $repositoryMock->expects($this->once())
            ->method('findByEnrollment')
            ->with($enrollmentId)
            ->willReturn(null);


        // Ci aspettiamo che save venga chiamato 1 volta con un'istanza di Certificate
        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Certificate::class));


        $useCase = new GenerateCertificateUseCase($repositoryMock);


        $result = $useCase->execute($enrollmentId, $userId, $coursedId);


        $this->assertInstanceOf(Certificate::class, $result);
        $this->assertSame($enrollmentId, $result->enrollmentId);
        $this->assertSame($userId, $result->userId);
        $this->assertSame($coursedId, $result->coursedId);
    }


    public function test_throws_exception_when_certificate_already_exists(): void
    {
        // Mock dell'interfaccia Repository
        $repositoryMock = $this->createMock(CertificateRepositoryInterface::class);


        $enrollmentId = 'enr-100';
        $existingCertificate = $this->createMock(Certificate::class);


        // Simuliamo che il certificato esista già
        $repositoryMock->expects($this->once())
            ->method('findByEnrollment')
            ->with($enrollmentId)
            ->willReturn($existingCertificate);


        // Ci aspettiamo che save NON venga mai chiamato
        $repositoryMock->expects($this->never())
            ->method('save');


        // Ci aspettiamo il lancio dell'eccezione RuntimeException
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Certificate already exists for this enrollment.');


        $useCase = new GenerateCertificateUseCase($repositoryMock);


        $useCase->execute($enrollmentId, 'user-42', 'course-99');
    }
}
