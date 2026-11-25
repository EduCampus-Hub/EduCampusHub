<?php

namespace App\Presentation\Controller;

use App\Application\DTO\CourseInput;
use App\Application\UseCase\CreateCourseUseCase;
use App\Application\UseCase\GetCourseUseCase;
use App\Application\UseCase\UpdateCourseUseCase;
use App\Application\UseCase\ViewAllCoursesUseCase;
use App\Presentation\Routing\CourseRoutes;
use InvalidArgumentException;
use RuntimeException;

class CourseController
{
    public function __construct(
        private CreateCourseUseCase $createCourseUseCase,
        private ViewAllCoursesUseCase $viewAllCoursesUseCase,
        private GetCourseUseCase $getCourseUseCase,
        private UpdateCourseUseCase $updateCourseUseCase
    ) {}

    public function index(): void
    {
        $courses = $this->viewAllCoursesUseCase->execute();
        $pageTitle = 'Corsi';
        $view = 'catalog.php';
        require __DIR__ . '/../Views/Partials/course-layout.php';
    }

    public function handle(): void
    {
        $this->showForm();
    }

    public function edit(string $id): void
    {
        $this->showForm($id);
    }

    private function showForm(?string $id = null): void
    {
        $errorMessage = null;
        $isEdit = $id !== null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values = $this->submittedValues($_POST);
            try {
                $input = new CourseInput(
                    maxCapacity: filter_var($values['maxCapacity'], FILTER_VALIDATE_INT) ?: 0,
                    targetAudience: $values['targetAudience'],
                    status: $values['status'],
                    title: trim($values['title']),
                    description: trim($values['description']),
                    isMandatory: $values['isMandatory'],
                    durationInHours: filter_var($values['durationInHours'], FILTER_VALIDATE_INT) ?: 0
                );

                if ($id === null) {
                    $this->createCourseUseCase->execute($input);
                } elseif (!$this->updateCourseUseCase->execute($id, $input)) {
                    $this->courseNotFound();
                    return;
                }

                header('Location: ' . CourseRoutes::INDEX, true, 303);
                return;
            } catch (InvalidArgumentException $exception) {
                http_response_code(422);
                $errorMessage = match ($exception->getMessage()) {
                    CourseInput::MISSING_TEXT => 'Inserisci il titolo e la descrizione del corso.',
                    CourseInput::INVALID_NUMBERS => 'Inserisci numeri interi maggiori di zero per la durata e la capienza.',
                    CourseInput::INVALID_TARGET => 'Seleziona i destinatari del corso.',
                    CourseInput::INVALID_STATUS => 'Seleziona uno stato del corso.',
                    default => 'Controlla i dati inseriti.',
                };
            } catch (RuntimeException $exception) {
                error_log($exception->getMessage());
                http_response_code(500);
                $errorMessage = 'Impossibile salvare il corso. Riprova.';
            }
        } else {
            $course = $id === null ? null : $this->getCourseUseCase->execute($id);
            if ($isEdit && $course === null) {
                $this->courseNotFound();
                return;
            }

            $values = [
                'title' => $course?->title ?? '',
                'description' => $course?->description ?? '',
                'targetAudience' => $course?->target->value ?? 'all',
                'status' => $course?->status->value ?? 'draft',
                'isMandatory' => $course?->isMandatory ?? false,
                'durationInHours' => (string) ($course?->durationInHours ?? ''),
                'maxCapacity' => (string) ($course?->maxCapacity ?? ''),
            ];
        }

        $pageTitle = $isEdit ? 'Modifica corso' : 'Nuovo corso';
        $action = $id === null ? CourseRoutes::CREATE : CourseRoutes::editPath($id);
        $view = 'Create_course.php';
        require __DIR__ . '/../Views/Partials/course-layout.php';
    }

    private function submittedValues(array $post): array
    {
        $values = [];
        foreach (['title', 'description', 'targetAudience', 'status', 'durationInHours', 'maxCapacity'] as $field) {
            $values[$field] = is_string($post[$field] ?? null) ? $post[$field] : '';
        }
        $values['isMandatory'] = in_array($post['isMandatory'] ?? null, ['1', 'on'], true);

        return $values;
    }

    private function courseNotFound(): void
    {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo 'Corso non trovato.';
    }
}
