<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Model\Certificate;

class CertificateTest extends TestCase
{
    public function test_can_create_new_Certificate(): void 
    {
        $certificate = Certificate::Create
        (
            enrollmentId: "ENR001",
            userId: "USER001",
            coursedId: "COURSE001",
            issuedAt: "2026-11-22"
        );
        
        $this->assertNull($certificate->id);
        $this->assertEquals("ENR001", $certificate->enrollmentId);
       $this->assertEquals("USER001", $certificate->userId);
        $this->assertEquals("COURSE001", $certificate->coursedId);
        $this->assertEquals("2026-11-22", $certificate->issuedAt);
    }
    public function test_can_restore_existem_Certificate(): void 
    {
        $certificate = Certificate::restore(
            id: 10,
            enrollmentId: 'ENR001',
            userId: 'USER001',
            coursedId: 'COURSE001',
            issuedAt: '2026-11-22',
            certificateCode: 'CERT001'
        );


        $this->assertEquals(10, $certificate->id);
        $this->assertEquals('ENR001', $certificate->enrollmentId);
        $this->assertEquals('USER001', $certificate->userId);
        $this->assertEquals('COURSE001', $certificate->coursedId);
        $this->assertEquals('2026-11-22', $certificate->issuedAt);
        $this->assertEquals('CERT001', $certificate->certificateCode);
    }


}