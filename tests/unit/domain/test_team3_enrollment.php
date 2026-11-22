<?php

namespace tests\unit\domain;

use App\Domain\Model\Enrollment;
use PHPUnit\Framework\TestCase;

class test_team3_enrollment extends TestCase
{
    public function testEnrollmentCreation(): void
    {
        $creation = Enrollment::create(
        'dfv',   
        'U2',
            'C101',
            new DateTime('2026-11-08 10:00:00'),
            'PENDING'
        );

        $this->assertInstanceOf(Enrollment::class, $creation);

        $this->assertEquals('U2', $creation->user_id);
        $this->assertEquals('C101', $creation->course_id);
        $this->assertEquals('2026-11-08 10:00:00', $creation->enrollment_at);
        $this->assertEquals(EnrollmentStatus::PENDING, $creation->status);
    }

    public function testEnrollmentRestore(): void
    {
        $restoration = Enrollment::restore(
         'dfv',    
        'U2',
            'C101',
             new DateTime('2026-11-08 10:00:00'),
            'PENDING'
        );

        $this->assertInstanceOf(Enrollment::class, $restoration);

        $this->assertEquals('U2', $restoration->user_id);
        $this->assertEquals('C101', $restoration->course_id);
        $this->assertEquals('2026-11-08 10:00:00', $restoration->enrollment_at);
        $this->assertEquals(EnrollmentStatus::PENDING, $restoration->status);
    }
}