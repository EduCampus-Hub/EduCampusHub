<?php

namespace Tests\Unit;

use App\Application\UseCase\ViewAllCoursesUseCase;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ViewAllCoursesUseCaseTest extends TestCase
{
    public function test_execute_returns_all_courses(): void
    {
        $courses = [
            Course::restore(
                id: 'course-1',
                title: 'PHP Programming',
                description: 'Corso avanzato di PHP',
                target: TargetAudienceEnum::STUDENTS,
                status: CourseStatusEnum::ACTIVE,
                isMandatory: true,
                durationInHours: 40,
                maxCapacity: 30
            ),
            Course::restore(
                id: 'course-2',
                title: 'Database',
                description: 'Corso sui database',
                target: TargetAudienceEnum::ALL,
                status: CourseStatusEnum::DRAFT,
                isMandatory: false,
                durationInHours: 20,
                maxCapacity: 25
            ),
        ];

        $repository = $this->createMock(CourseRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $useCase = new ViewAllCoursesUseCase($repository);

        $this->assertSame($courses, $useCase->execute());
    }
}
