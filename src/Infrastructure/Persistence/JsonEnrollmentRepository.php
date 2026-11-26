<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Enrollment;
use App\Domain\Enum\Status;
use App\Domain\Repository\EnrollmentRepositoryInterface;
use RuntimeException;


class JsonEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function __construct(
        private string $filePath
    ) {}

    public function findById(string $id): ?Enrollment
    {
        foreach ($this->findAll() as $enrollment) {
            if ($enrollment->id === $id) {
                return $enrollment;
            }
        }

        return null;
    }

    public function findByUserId(string $id): array
    {
        return array_values(array_filter(
            $this->findAll(),
            static fn(Enrollment $enrollment): bool => $enrollment->user_id === $id
        ));
    }

    public function findByUserAndCourse(string $userId, string $courseId): ?Enrollment
    {
        foreach ($this->findAll() as $enrollment) {
            if ($enrollment->user_id === $userId && $enrollment->course_id === $courseId) {
                return $enrollment;
            }
        }

        return null;
    }

    public function countByCourse(string $courseId): int
    {
        return count(array_filter(
            $this->findAll(),
            static fn(Enrollment $enrollment): bool => $enrollment->course_id === $courseId
        ));
    }

    public function save(Enrollment $enrollment): bool
    {
        $enrollments = $this->findAll();

        $existing = false;
        $data = [];
        foreach ($enrollments as $item) {
            if ($item->id === $enrollment->id) {
                $item = $enrollment;
                $existing = true;
            }

            $data[] = $this->serialize($item);
        }

        if (!$existing) {
            $data[] = $this->serialize($enrollment);
        }

        $directory = dirname($this->filePath);
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException("Impossibile creare la directory: {$directory}");
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents($this->filePath, $json) === false) {
            throw new RuntimeException("Impossibile salvare il file delle iscrizioni: {$this->filePath}");
        }

        return true;
    }

    public function findAll(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = trim((string) file_get_contents($this->filePath));
        if ($content === '') {
            return [];
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            throw new RuntimeException("Il file delle iscrizioni contiene JSON non valido: {$this->filePath}");
        }

        $enrollments = [];
        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }

            $userId = $item['user_id'] ?? $item['userId'] ?? null;
            $courseId = $item['course_id'] ?? $item['courseId'] ?? null;
            $enrolledAt = $item['enrolled_at'] ?? $item['enrolledAt'] ?? null;

            if (!isset($item['id'], $userId, $courseId, $enrolledAt)) {
                continue;
            }

            $statusName = $item['status'] ?? 'Pending';
            if (is_string($statusName)) {
                $statusName = match (strtoupper($statusName)) {
                    'PENDING' => 'Pending',
                    'CONFIRMED' => 'Confirmed',
                    'NEGATED' => 'Negated',
                    default => $statusName,
                };
                $status = Status::fromName($statusName);
            } else {
                $status = Status::Pending;
            }

            $enrollments[] = Enrollment::restore(
                id: (string) $item['id'],
                user_id: (string) $userId,
                course_id: (string) $courseId,
                enrolled_at: new \DateTime($enrolledAt),
                status: $status
            );
        }

        return $enrollments;
    }

    private function serialize(Enrollment $enrollment): array
    {
        return [
            'id' => $enrollment->id,
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
            'enrolled_at' => $enrollment->enrolled_at->format('Y-m-d H:i:s'),
            'status' => strtoupper($enrollment->status->name),
        ];
    }
}