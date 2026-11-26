<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use JsonException;
use RuntimeException;

class JsonCourseRepository implements CourseRepositoryInterface
{
    public function __construct(private string $filePath) {}

    public function save(Course $course): bool
    {
        // Il lock resta sullo stesso file anche quando il JSON viene sostituito.
        $lock = @fopen($this->filePath . '.lock', 'c');
        if ($lock === false) {
            return false;
        }

        $temporaryPath = false;
        try {
            if (!flock($lock, LOCK_EX)) {
                return false;
            }

            $records = $this->readRecords();
            $record = [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'target' => $course->target->value,
                'status' => $course->status->value,
                'isMandatory' => $course->isMandatory,
                'durationInHours' => $course->durationInHours,
                'maxCapacity' => $course->maxCapacity,
            ];

            $index = array_search($course->id, array_column($records, 'id'), true);
            $records[$index === false ? count($records) : $index] = $record;
            $json = json_encode($records, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $temporaryPath = @tempnam(dirname($this->filePath), '.courses-');
            if ($temporaryPath === false || @file_put_contents($temporaryPath, $json) !== strlen($json)) {
                return false;
            }

            if (!@rename($temporaryPath, $this->filePath)) {
                return false;
            }

            $temporaryPath = false;
            return true;
        } catch (JsonException) {
            return false;
        } finally {
            if ($temporaryPath !== false) {
                @unlink($temporaryPath);
            }
            fclose($lock);
        }
    }

    public function findById(string $id): ?Course
    {
        foreach ($this->readRecords() as $record) {
            if ($record['id'] === $id) {
                return $this->restoreCourse($record);
            }
        }

        return null;
    }

    public function findActiveByTarget(TargetAudienceEnum $target): array
    {
        $courses = [];
        foreach ($this->findAll() as $course) {
            if ($course->status !== CourseStatusEnum::ACTIVE) {
                continue;
            }

            if (
                $target === TargetAudienceEnum::ALL ||
                $course->target === $target ||
                $course->target === TargetAudienceEnum::ALL
            ) {
                $courses[] = $course;
            }
        }

        return $courses;
    }

    public function findAll(): array
    {
        return array_map($this->restoreCourse(...), $this->readRecords());
    }

    private function readRecords(): array
    {
        clearstatcache(true, $this->filePath);
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        if ($json === false) {
            throw new RuntimeException('Unable to read the course storage.');
        }
        if (trim($json) === '') {
            return [];
        }

        try {
            // Restituisce i tipi scalari. Gli enum vengono ricostruiti da restoreCourse().
            $records = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Invalid course storage.', previous: $exception);
        }

        if ($records === null) {
            return [];
        }
        if (!is_array($records) || !array_is_list($records)) {
            throw new RuntimeException('Invalid course storage.');
        }

        return $records;
    }

    private function restoreCourse(array $record): Course
    {
        return Course::restore(
            id: $record['id'],
            title: $record['title'],
            description: $record['description'],
            target: TargetAudienceEnum::from($record['target']),
            status: CourseStatusEnum::from($record['status']),
            isMandatory: $record['isMandatory'],
            durationInHours: $record['durationInHours'],
            maxCapacity: $record['maxCapacity']
        );
    }
}
