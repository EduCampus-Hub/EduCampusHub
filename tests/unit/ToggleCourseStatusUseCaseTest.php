<?php

namespace Tests\Unit;

use App\Application\UseCase\ToggleCourseStatusUseCase;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ToggleCourseStatusUseCaseTest extends TestCase
{
    public function test_executes_status_toggle_and_saves_course(): void
    {
        $existingCourse = Course::restore(
            id: 'course-1',
            title: 'PHP Programming',
            description: 'Corso avanzato di PHP',
            target: TargetAudienceEnum::STUDENTS,
            status: CourseStatusEnum::DRAFT,
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        );

        $repository = $this->createMock(CourseRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->with('course-1')
            ->willReturn($existingCourse);

        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Course $course): bool {
                return $course->id === 'course-1'
                    && $course->title === 'PHP Programming'
                    && $course->status === CourseStatusEnum::ACTIVE;
            }))
            ->willReturn(true);

        $useCase = new ToggleCourseStatusUseCase($repository);
        $useCase->execute('course-1', CourseStatusEnum::ACTIVE);

        $this->addToAssertionCount(1);
    }
}
