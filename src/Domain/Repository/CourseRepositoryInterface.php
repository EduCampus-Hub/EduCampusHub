<?php
namespace App\Domain\Repository;
use App\Domain\Model\Course;
use App\Domain\Enum\TargetAudienceEnum;

interface CourseRepositoryInterface
{
    public function save(Course $course): bool;
    public function findById(string $id): ?Course;
    public function findActiveByTarget(TargetAudienceEnum $target): array;
    public function findAll(): array;
}