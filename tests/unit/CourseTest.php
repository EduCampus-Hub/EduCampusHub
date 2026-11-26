<?php

namespace Tests\Unit;

use App\Domain\Model\Course;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testCreateCourse(): void
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

        $this->assertNotEmpty($course->id);
        $this->assertEquals('Programmazione PHP', $course->title);
        $this->assertEquals('Corso introduttivo', $course->description);
        $this->assertEquals(TargetAudienceEnum::STUDENTS, $course->target);
        $this->assertEquals(CourseStatusEnum::DRAFT, $course->status);
        $this->assertTrue($course->isMandatory);
        $this->assertEquals(40, $course->durationInHours);
        $this->assertEquals(30, $course->maxCapacity);
    }

    public function testRestoreCourse(): void
    {
        $course = Course::restore(
            id: '1',
            title: 'Programmazione PHP',
            description: 'Corso introduttivo',
            target: TargetAudienceEnum::STUDENTS,
            status: CourseStatusEnum::ACTIVE,
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        );

        $this->assertEquals('1', $course->id);
        $this->assertEquals('Programmazione PHP', $course->title);
        $this->assertEquals('Corso introduttivo', $course->description);
        $this->assertEquals(TargetAudienceEnum::STUDENTS, $course->target);
        $this->assertEquals(CourseStatusEnum::ACTIVE, $course->status);
        $this->assertTrue($course->isMandatory);
        $this->assertEquals(40, $course->durationInHours);
        $this->assertEquals(30, $course->maxCapacity);
    }

    public function testDifferentIds(): void
    {
        $course1 = Course::create(
            title: 'PHP',
            description: 'Corso PHP',
            target: TargetAudienceEnum::STUDENTS,
            status: CourseStatusEnum::DRAFT,
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        );

        $course2 = Course::create(
            title: 'PHP',
            description: 'Corso PHP',
            target: TargetAudienceEnum::STUDENTS,
            status: CourseStatusEnum::DRAFT,
            isMandatory: true,
            durationInHours: 40,
            maxCapacity: 30
        );

        $this->assertNotEquals($course1->id, $course2->id);
    }

    public function testOptionalCourse(): void
    {
        $course = Course::create(
            title: 'SQL',
            description: 'Corso SQL',
            target: TargetAudienceEnum::ALL,
            status: CourseStatusEnum::ACTIVE,
            isMandatory: false,
            durationInHours: 20,
            maxCapacity: 15
        );

        $this->assertFalse($course->isMandatory);
        $this->assertEquals(TargetAudienceEnum::ALL, $course->target);
        $this->assertEquals(CourseStatusEnum::ACTIVE, $course->status);
    }
}