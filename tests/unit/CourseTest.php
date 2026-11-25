<?php

namespace Tests\Unit;

use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Model\Course;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function test_create_populates_the_course_and_generates_a_version_four_uuid(): void
    {
        $course = Course::create(
            title: 'Programmazione PHP',
            description: 'Corso introduttivo',
            target: TargetAudienceEnum::STUDENTS,
            status: CourseStatusEnum::DRAFT,
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        );

        $this->assertMatchesRegularExpression(
            '/\A[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}\z/',
            $course->id
        );
        $this->assertSame('Programmazione PHP', $course->title);
        $this->assertSame('Corso introduttivo', $course->description);
        $this->assertSame(TargetAudienceEnum::STUDENTS, $course->target);
        $this->assertSame(CourseStatusEnum::DRAFT, $course->status);
        $this->assertTrue($course->isMandatory);
        $this->assertSame(40, $course->durationInHours);
        $this->assertSame(30, $course->maxCapacity);
    }

    public function test_creating_two_courses_with_the_same_details_generates_different_ids(): void
    {
        $first = Course::create('PHP', 'Introduzione', TargetAudienceEnum::ALL, CourseStatusEnum::DRAFT, false, 8, 20);
        $second = Course::create('PHP', 'Introduzione', TargetAudienceEnum::ALL, CourseStatusEnum::DRAFT, false, 8, 20);

        $this->assertNotSame($first->id, $second->id);
    }

    public function test_restore_keeps_the_existing_id_and_all_saved_values(): void
    {
        $course = Course::restore(
            id: 'course-1',
            title: 'Didattica digitale',
            description: 'Laboratorio per docenti',
            target: TargetAudienceEnum::TEACHERS,
            status: CourseStatusEnum::ACTIVE,
            isMandatory: false,
            durationInHours: 12,
            maxCapacity: 25
        );

        $this->assertSame('course-1', $course->id);
        $this->assertSame('Didattica digitale', $course->title);
        $this->assertSame('Laboratorio per docenti', $course->description);
        $this->assertSame(TargetAudienceEnum::TEACHERS, $course->target);
        $this->assertSame(CourseStatusEnum::ACTIVE, $course->status);
        $this->assertFalse($course->isMandatory);
        $this->assertSame(12, $course->durationInHours);
        $this->assertSame(25, $course->maxCapacity);
    }
}
