<?php

namespace Infrastructure\Persistence;

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
        $enrollments = $this->findAll();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->id === (int) $id) {
                return $enrollment;
            }
        }

        return null;
    }
    public function findByUserId(string $id): ?Enrollment
    {
        return null;
    }
    public function findByUserAndCourse(string $userId, string $courseId): ?Enrollment
    {
        $enrollments = $this->findAll();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id === $userId && $enrollment->course_id === $courseId) {
                return $enrollment;
            }
        }

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

    public function findAll(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = file_get_contents($this->filePath);
        // Rimuove eventuali caratteri UTF-8 BOM o spazi invisibili
        $content = trim($content);
        $data = json_decode($content, true);

        if ($data === null) {
            return [];
        }

        $requests = [];
        foreach ($data as $item) {
            // Verifica che l'elemento sia un array con tutte le chiavi presenti
            $status = Status::fromName($item['status'] ?? 'Pending');

            if (
                is_array($item) &&
                isset($item['id'], $item['userId'], $item['courseId'], $item['enrolledAt'])
            ) {
                $requests[] = Enrollment::restore(
                    id: (int) $item['id'],
                    user_id: (string) $item['userId'],
                    course_id: (string) $item['courseId'],
                    enrolled_at: new \DateTime($item['enrolledAt']),
                    status: $status
                );
            }
        }

        return $requests;
    }
    
}