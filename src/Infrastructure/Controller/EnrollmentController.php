<?php

namespace App\Infrastructure\Controller;

use App\Application\DTO\CreateEnrollUserDTO;
use App\Application\DTO\ReadEnrollUserDTO;
use App\Application\UseCase\EnrollUserUseCase;
use App\Application\UseCase\GetUserEnrollUseCase;
use InvalidArgumentException;
use RuntimeException;

class EnrollmentController
{
    public function __construct(
        private EnrollUserUseCase $enrollUserUseCase,
        private GetUserEnrollUseCase $getUserEnrollUseCase
    ) {}

    public function index(): void
    {
        $userId = $this->currentUserId();
        $enrollments = $this->getUserEnrollments($userId);
        $pageTitle = 'Le mie iscrizioni';
        $view = 'enrollments.php';
        require __DIR__ . '/../Views/enrollment-layout.php';
    }

    public function handle(): void
    {
        $errorMessage = null;
        $successMessage = null;
        $values = [
            'user_id' => $this->currentUserId(),
            'course_id' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values = $this->submittedValues($_POST);

            try {
                $dto = new CreateEnrollUserDTO(
                    user_id: trim($values['user_id']),
                    course_id: trim($values['course_id'])
                );

                $this->enrollUserUseCase->execute($dto);
                $successMessage = 'Richiesta di iscrizione inviata.';
            } catch (InvalidArgumentException $exception) {
                http_response_code(422);
                $errorMessage = $exception->getMessage();
            } catch (RuntimeException $exception) {
                error_log($exception->getMessage());
                http_response_code(500);
                $errorMessage = 'Impossibile salvare l\'iscrizione. Riprova.';
            }
        }

        $userId = trim($values['user_id']);
        $enrollments = $this->getUserEnrollments($userId);
        $pageTitle = 'Iscriviti a un corso';
        $view = 'enrollment-form.php';
        require __DIR__ . '/../Views/enrollment-layout.php';
    }

    private function submittedValues(array $post): array
    {
        $userId = is_string($post['user_id'] ?? null)
            ? trim($post['user_id'])
            : $this->currentUserId();
        $courseId = is_string($post['course_id'] ?? null)
            ? trim($post['course_id'])
            : '';

        return [
            'user_id' => $userId,
            'course_id' => $courseId,
        ];
    }

    private function currentUserId(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? $_POST['user_id'] ?? '';

        return is_string($userId) ? trim($userId) : '';
    }

    private function getUserEnrollments(string $userId): array
    {
        if ($userId === '') {
            return [];
        }

        $enrollments = $this->getUserEnrollUseCase->execute($userId);

        return array_map(static fn($enrollment) => new ReadEnrollUserDTO(       //array_map serve al posto di in foreach
            user_id: $enrollment->user_id,
            course_id: $enrollment->course_id,
            status: $enrollment->status->name,
            created_at: $enrollment->enrolled_at->format('Y-m-d H:i:s')
        ), $enrollments);
    }
}