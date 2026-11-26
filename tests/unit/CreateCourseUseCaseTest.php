<?php

namespace Tests\Unit;

use App\Application\DTO\CreateCourseDTO;
use App\Application\UseCase\CreateCourseUseCase;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateCourseUseCaseTest extends TestCase
{
    public function test_executes_create_course_and_saves_it(): void
    {
        $repository = $this->createMock(CourseRepositoryInterface::class);

        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Course $course): bool {
                return $course->title === 'PHP Programming'
                    && $course->description === 'Corso avanzato di PHP'
                    && $course->target === TargetAudienceEnum::STUDENTS
                    && $course->status === CourseStatusEnum::DRAFT
                    && $course->isMandatory === true
                    && $course->durationInHours === 40
                    && $course->maxCapacity === 30;
            }))
            ->willReturn(true);

        $useCase = new CreateCourseUseCase($repository);

        $useCase->execute(new CreateCourseDTO(
            id: 'course-input',
            title: 'PHP Programming',
            description: 'Corso avanzato di PHP',
            targetAudience: 'STUDENTS',
            status: 'DRAFT',
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        ));

        $this->addToAssertionCount(1);
    }

    public function test_execute_rejects_empty_title(): void
    {
        $repository = $this->createMock(CourseRepositoryInterface::class);
        $repository->expects($this->never())->method('save');

        $useCase = new CreateCourseUseCase($repository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title and description are required.');

        $useCase->execute(new CreateCourseDTO(
            id: 'course-input',
            title: '',
            description: 'Descrizione',
            targetAudience: 'STUDENTS',
            status: 'DRAFT',
            isMandatory: false,
            durationInHours: 10,
            maxCapacity: 20
        ));
    }

    public function test_execute_rejects_invalid_capacity_and_duration(): void
    {
        $repository = $this->createMock(CourseRepositoryInterface::class);
        $repository->expects($this->never())->method('save');

        $useCase = new CreateCourseUseCase($repository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Max capacity and duration must be greater than zero.');

        $useCase->execute(new CreateCourseDTO(
            id: 'course-input',
            title: 'Corso',
            description: 'Descrizione',
            targetAudience: 'ALL',
            status: 'DRAFT',
            isMandatory: false,
            durationInHours: 0,
            maxCapacity: 0
        ));
    }
}
