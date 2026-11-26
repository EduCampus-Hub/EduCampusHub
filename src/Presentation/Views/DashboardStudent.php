<?php
$agenda = $agenda ?? [];
$enrollments = $enrollments ?? [];

$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$getValue = static function (object|array $item, string $property, mixed $fallback = ''): mixed {
    if (is_array($item)) {
        return $item[$property] ?? $fallback;
    }

    return $item->{$property} ?? $fallback;
};
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda formativa studente</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<main class="dashboard">
    <header class="header">
        <div>
            <h1>Le mie iscrizioni</h1>
            <p>Agenda formativa personale e attestati.</p>
        </div>
    </header>

    <?php $courseList = !empty($enrollments) ? $enrollments : $agenda; ?>

    <?php if (empty($courseList)): ?>
        <section class="panel">
            <p class="empty">Nessun corso presente nell'agenda formativa.</p>
        </section>
    <?php else: ?>
        <section class="course-grid" aria-label="Corsi dello studente">
            <?php foreach ($courseList as $enrollment): ?>
                <?php
                $courseName = $getValue($enrollment, 'title', $getValue($enrollment, 'titolo', $getValue($enrollment, 'corso', 'Corso')));
                $description = $getValue($enrollment, 'description', $getValue($enrollment, 'descrizione', ''));
                $hours = $getValue($enrollment, 'durationInHours', $getValue($enrollment, 'ore', $getValue($enrollment, 'durata', '-')));
                $capacity = $getValue($enrollment, 'maxCapacity', $getValue($enrollment, 'capienza', '-'));
                $id = $getValue($enrollment, 'id', $getValue($enrollment, 'courseId', ''));
                $status = strtolower((string) $getValue($enrollment, 'stato', 'ISCRITTO'));
                ?>
                <article class="course-card">
                    <div class="course-card-header">
                        <h2><?= $escape($courseName) ?></h2>
                        <span class="badge <?= $status === 'completato' ? 'attivo' : 'bozza' ?>">
                            <?= $escape(strtoupper($status)) ?>
                        </span>
                    </div>

                    <?php if ($description !== ''): ?>
                        <p><?= $escape($description) ?></p>
                    <?php endif; ?>

                    <div class="meta">
                        <strong>Durata:</strong> <?= $escape($hours) ?> ore
                        <span aria-hidden="true"> | </span>
                        <strong>Capienza:</strong> <?= $escape($capacity) ?>
                    </div>

                    <div class="actions">
                        <?php if ($id !== ''): ?>
                            <a class="btn btn-secondary" href="index.php?action=details_course&amp;id=<?= urlencode((string) $id) ?>">Visualizza informazioni</a>
                        <?php endif; ?>

                        <?php if ($status === 'completato'): ?>
                            <a class="btn btn-success" href="index.php?action=download_certificate&amp;id=<?= urlencode((string) $id) ?>">Scarica attestato (PDF)</a>
                        <?php endif; ?>

                        <a class="btn" href="index.php?action=upload_certificate&amp;id=<?= urlencode((string) $id) ?>">Carica PDF</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <section class="panel" aria-labelledby="upload-title">
        <div class="panel-header">
            <h2 id="upload-title">Carica attestato personale</h2>
        </div>

        <form class="upload-form form-grid" method="post" action="index.php?action=upload_certificate" enctype="multipart/form-data">
            <label>
                Corso associato
                <input type="text" name="course_id" placeholder="ID corso">
            </label>

            <label>
                File PDF
                <input type="file" name="certificate" accept="application/pdf" required>
            </label>

            <div class="form-actions">
                <button class="btn" type="submit">Carica PDF</button>
            </div>
        </form>
    </section>
</main>

</body>
</html>

