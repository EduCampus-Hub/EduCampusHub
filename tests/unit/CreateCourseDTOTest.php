<?php

namespace Tests\Unit;

use App\Application\DTO\CreateCourseDTO;
use PHPUnit\Framework\TestCase;

class CreateCourseDTOTest extends TestCase
{
    public function test_create_course_dto_contains_all_values(): void
    {
        $dto = new CreateCourseDTO(
            maxCapacity: 30,
            targetAudience: 'students',
            status: 'draft',
            id: 'course-1',
            title: 'PHP Programming',
            description: 'Corso avanzato di PHP',
            isMandatory: true,
            durationInHours: 40
        );

        $this->assertSame(30, $dto->maxCapacity);
        $this->assertSame('students', $dto->targetAudience);
        $this->assertSame('draft', $dto->status);
        $this->assertSame('course-1', $dto->id);
        $this->assertSame('PHP Programming', $dto->title);
        $this->assertSame('Corso avanzato di PHP', $dto->description);
        $this->assertTrue($dto->isMandatory);
        $this->assertSame(40, $dto->durationInHours);
    }
}
