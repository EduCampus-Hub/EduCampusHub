<?php

namespace App\Application\UseCase;

use App\Application\DTO\CreateEnrollUserDTO;
use App\Domain\Enum\Status;
use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;
use DateTime;

class GetCourseEnrollmentsUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository
    ) {
    }

    public function execute(string $course_id): int
    {
        return $this->enrollmentRepository->countByCourse($course_id);
    }
}