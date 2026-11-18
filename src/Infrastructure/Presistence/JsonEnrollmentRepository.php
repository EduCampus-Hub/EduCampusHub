<?php

namespace Infrastructure\Persistence;

use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;


class JsonEnrollmentRepository implements EnrollmentRepositoryInterface{
    public function __construct(
        private string $filePath
    ) {}

    public function findById(string $id): ?Enrollment
    {
        return null;
    }
    public function findByUserId(string $id): ?Enrollment
    {
        return null;
    }
    public function findByUserAndCourse(string $userId, string $courseId): ?Enrollment
    {
        return null;
    }
    public function countByCourse(string $courseId): int
    {
        return 0;
    }
    public function save(Enrollment $enrollment): bool
    {
        $enrollments = $this->findAll();

        $data = array_map(function (Enrollment $enroll) {
            return [
                'id' => $enroll->id,
                'userId' => $enroll->user_id,
                'courseId' => $enroll->course_id,
                'enrolledAt' => $enroll->enrolled_at->format('Y-m-d H:i:s'),
            ];
        }, $enrollments);

        $data[] = [
            'id' => $enrollment->id,
            'userId' => $enrollment->user_id,
            'courseId' => $enrollment->course_id,
            'enrolledAt' => $enrollment->enrolled_at->format('Y-m-d H:i:s'),
        ];

        file_put_contents(
            $this->filePath, 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return true;
    }

    private function findAll(): array
    {
        
        
        if (!file_exists($this->filePath)) {
            return [];
        }

        $jsonData = file_get_contents($this->filePath);
        return json_decode($jsonData, true) ?? [];
    }
}