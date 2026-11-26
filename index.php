<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Application\DTO\CreateEnrollUserDTO;
use App\Application\UseCase\EnrollUserUseCase;
use App\Application\UseCase\GetUserEnrollUseCase;
use App\Infrastructure\Persistence\JsonEnrollmentRepository;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$repository = new JsonEnrollmentRepository(__DIR__ . '/storage/json/enrollment.json');
$enrollUserUseCase = new EnrollUserUseCase($repository);
$getUserEnrollUseCase = new GetUserEnrollUseCase($repository);

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requestPath = '/' . trim($requestPath, '/');

$errorMessage = null;
$successMessage = null;
$userId = isset($_SESSION['user_id']) && is_string($_SESSION['user_id'])
    ? trim($_SESSION['user_id'])
    : '';
$courseId = '';

if ($requestMethod === 'POST' && $requestPath === '/enroll') {
    $submittedUserId = isset($_POST['user_id']) && is_string($_POST['user_id'])
        ? trim($_POST['user_id'])
        : '';
    $submittedCourseId = isset($_POST['course_id']) && is_string($_POST['course_id'])
        ? trim($_POST['course_id'])
        : '';

    $userId = $submittedUserId;
    $courseId = $submittedCourseId;

    if ($userId !== '') {
        $_SESSION['user_id'] = $userId;
    }

    try {
        $enrollUserUseCase->execute(new CreateEnrollUserDTO($userId, $courseId));
        $successMessage = 'Richiesta di iscrizione inviata.';
    } catch (InvalidArgumentException | RuntimeException $exception) {
        http_response_code($exception instanceof InvalidArgumentException ? 422 : 500);
        $errorMessage = $exception->getMessage();
    }
}

$enrollments = $userId === '' ? [] : $getUserEnrollUseCase->execute($userId);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduCampusHub - Iscrizioni</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; }
        body { margin: 0; background: #f4f7fb; color: #1f2937; }
        main { max-width: 760px; margin: 48px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; padding: 28px; box-shadow: 0 8px 24px rgba(31, 41, 55, .08); }
        h1, h2 { margin-top: 0; }
        form { display: grid; gap: 14px; }
        label { font-weight: 700; }
        input { width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; }
        button { border: 0; border-radius: 8px; padding: 12px 16px; background: #2563eb; color: white; font-weight: 700; cursor: pointer; }
        .message { padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<main>
    <section class="card">
        <h1>EduCampusHub</h1>

        <?php if ($successMessage !== null): ?>
            <div class="message success"><?= h($successMessage) ?></div>
        <?php endif; ?>
        <?php if ($errorMessage !== null): ?>
            <div class="message error"><?= h($errorMessage) ?></div>
        <?php endif; ?>

        <?php if ($requestPath === '/enroll'): ?>
            <h2>Iscriviti a un corso</h2>
            <form method="post" action="/enroll">
                <div>
                    <label for="user_id">ID utente</label>
                    <input id="user_id" name="user_id" value="<?= h($userId) ?>" required>
                </div>
                <div>
                    <label for="course_id">ID corso</label>
                    <input id="course_id" name="course_id" value="<?= h($courseId) ?>" required>
                </div>
                <button type="submit">Invia iscrizione</button>
            </form>
            <p><a href="/">Torna alle mie iscrizioni</a></p>
        <?php else: ?>
            <h2>Le mie iscrizioni</h2>
            <p><a href="/enroll">Iscriviti a un corso</a></p>

            <?php if ($userId === ''): ?>
                <p>Inserisci il tuo ID dalla pagina di iscrizione.</p>
            <?php elseif ($enrollments === []): ?>
                <p>Nessuna iscrizione trovata.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr><th>Corso</th><th>Stato</th><th>Data</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td><?= h($enrollment->course_id) ?></td>
                            <td><?= h($enrollment->status->name) ?></td>
                            <td><?= h($enrollment->enrolled_at->format('Y-m-d H:i:s')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>
</body>
</html>