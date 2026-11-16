<?php

namespace App\Domain\Repository;

interface EnrollmentRepositoryInterface
{
    public function findById(string $id): ?Enrollment;
    public function findByUserId(string $id):?Enrollment;
    public function findByUserAndCourse(string $userId, string $courseId): ?Enrollment;
    public function countByCourse(string $courseId): int;
    public function save (Enrollment $enrollment): bool;
}