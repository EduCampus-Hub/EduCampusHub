<?php
namespace tests\unit\domain;

class Team3EnrollmentTest extends TestCase{
    public function testEnrollmentCreation():void{
         //test da completare perché non ho ancora la classe

        $creation= Enrollment::create('U2', 'C101', '2026-11-08 10:00:00', 'PENDING');
        $this->assertNull
        $this->assertEquals('U2', $creation->$user_id);
        $this->assertEquals('C101', $creation->$course_id);
        $this->assertEquals('2026-11-08 10:00:00', $creation->$enrollment_at);
        $this->assertEquals('PENDING', $creation->$status);
    }
    public function testEnrollmentRestore():void
    {
        //test da completare perché non ho ancora la classe
        $restoration=Enrollment::restore('U2', 'C101', '2026-11-08 10:00:00', 'PENDING');
        $this->assertNull
        $this->assertEquals('U2', $restoration->$user_id);
        $this->assertEquals('C101', $restoration->$course_id);
        $this->assertEquals('2026-11-08 10:00:00', $restoration->$enrollment_at);
        $this->assertEquals('PENDING', $restoration->$status);
    }
}