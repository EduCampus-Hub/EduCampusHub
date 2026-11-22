<?php

namespace tests\unit\domain;

use App\Domain\Model\Enrollment;
use App\Domain\Enum\Status;
use PHPUnit\Framework\TestCase;
use DateTime;

class Team3EnrollmentTest extends TestCase
{
    public function testEnrollmentCreation(): void
    {
        $creation = Enrollment::create(
            'dfv',
            'U2',
            'C101',
            new DateTime('2026-11-08 10:00:00'),
            Status::Pending
        );

        $this->assertInstanceOf(Enrollment::class, $creation);

        $this->assertEquals('U2', $creation->user_id);
        $this->assertEquals('C101', $creation->course_id);
        $this->assertEquals(
            new DateTime('2026-11-08 10:00:00'),
            $creation->enrolled_at
        );
        $this->assertEquals(Status::Pending, $creation->status);
    }

    public function testEnrollmentRestore(): void
    {
        $restoration = Enrollment::restore(
            'dfv',
            'U2',
            'C101',
            new DateTime('2026-11-08 10:00:00'),
            Status::Pending
        );

        $this->assertInstanceOf(Enrollment::class, $restoration);

        $this->assertEquals('U2', $restoration->user_id);
        $this->assertEquals('C101', $restoration->course_id);
        $this->assertEquals(
            new DateTime('2026-11-08 10:00:00'),
            $restoration->enrolled_at
        );
        $this->assertEquals(Status::Pending, $restoration->status);
    }
}