<!DOCTYPE html>
<html lang="it" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Corsi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
</head>

<body class="bg-1">
    <div class="container py-5">
        <h1 class="mb-4 pb-3 border-bottom border-primary">Corsi</h1>

        <div class="mb-4">
            <a class="btn btn-primary" href="/corsi/nuovo">Nuovo corso</a>
        </div>

        <section>
            <?php if (empty($courses)): ?>
                <div class="alert theme-secondary">Nessun corso presente.</div>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <div class="card mb-3 shadow-sm border-start border border-primary">
                        <div class="card-body">
                            <h3 class="card-title fs-xl fw-medium"><?= htmlspecialchars($course->title) ?></h3>
                            <p class="card-text mb-0"><?= htmlspecialchars($course->description) ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between fs-sm">
                            <span><strong>Destinatari:</strong> <?= htmlspecialchars($course->target->value) ?></span>
                            <span><strong>Stato:</strong> <?= htmlspecialchars($course->status->value) ?></span>
                            <span><strong>Durata:</strong> <?= $course->durationInHours ?> ore</span>
                            <span><strong>Posti:</strong> <?= $course->maxCapacity ?></span>
                            <a href="/corsi/modifica?id=<?= htmlspecialchars($course->id) ?>">Modifica</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>
</body>

</html>
