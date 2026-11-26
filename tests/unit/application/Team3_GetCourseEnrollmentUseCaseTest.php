<?php

namespace tests\unit\application;

use PHPUnit\Framework\TestCase;
use App\Application\UseCase\GetCourseEnrollmentsUseCase;
use App\Domain\Repository\EnrollmentRepositoryInterface;

class Team3_GetCourseEnrollmentUseCaseTest extends TestCase
{
    public function testReturnsEnrollmentCountForCourse(): void
    {
        $repository = $this->createMock(EnrollmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('countByCourse')
            ->with('course-1')
            ->willReturn(3);

        $useCase = new GetCourseEnrollmentsUseCase($repository);

        $this->assertSame(3, $useCase->execute('course-1'));
    }

    public function testReturnsZeroWhenCourseHasNoEnrollments(): void
    {
        $repository = $this->createMock(EnrollmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('countByCourse')
            ->with('course-2')
            ->willReturn(0);

        $useCase = new GetCourseEnrollmentsUseCase($repository);

        $this->assertSame(0, $useCase->execute('course-2'));
    }
}
