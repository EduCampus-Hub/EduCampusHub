<?php
namespace tests\unit\application;
use PHPUnit\Framework\TestCase;
use App\Application\UseCase\GetUserEnrollUseCase;
use App\Domain\Enum\Status;
use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;
use DateTime;
class Team3_GetEnrollUserUseCaseTest extends TestCase
{
    public function testReturnsEnrollmentsForUser(): void
    {
        $enrollment = Enrollment::restore(
            '1',
            'user-1',
            'course-1',
            new DateTime('2026-11-08 10:00:00'),
            Status::Pending
        );

        $repository = $this->createMock(EnrollmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByUserId')
            ->with('user-1')
            ->willReturn([$enrollment]);

        $useCase = new GetUserEnrollUseCase($repository);

        $this->assertSame([$enrollment], $useCase->execute('user-1'));
    }

    public function testReturnsEmptyArrayWhenUserHasNoEnrollments(): void
    {
        $repository = $this->createMock(EnrollmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByUserId')
            ->with('user-2')
            ->willReturn([]);

        $useCase = new GetUserEnrollUseCase($repository);

        $this->assertSame([], $useCase->execute('user-2'));
    }
}