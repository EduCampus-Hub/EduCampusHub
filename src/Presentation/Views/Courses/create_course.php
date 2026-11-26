<!DOCTYPE html>
<html lang="it" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <h1 class="pb-3 border-bottom border-primary"><?= isset($course) ? 'Modifica Corso' : 'Nuovo Corso' ?></h1>

        <a class="btn btn-secondary my-4" href="/corsi">Torna alla lista</a>
        <form action="<?= isset($course) ? '/corsi/modifica?id=' . htmlspecialchars($course->id) : '/corsi/nuovo' ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titolo</label>
                <input type="text" class="form-control" id="title" name="title" required
                    value="<?= htmlspecialchars($course->title ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                    required><?= htmlspecialchars($course->description ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="targetAudience" class="form-label">Destinatari</label>
                <select class="form-select" id="targetAudience" name="targetAudience" required>
                    <option value="all">Tutti</option>
                    <option value="students" <?= ($course->target->value ?? '') === 'students' ? 'selected' : '' ?>>Studenti</option>
                    <option value="teachers" <?= ($course->target->value ?? '') === 'teachers' ? 'selected' : '' ?>>Docenti</option>
                    <option value="atc" <?= ($course->target->value ?? '') === 'atc' ? 'selected' : '' ?>>Personale ATC</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Stato</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft">Bozza</option>
                    <option value="active" <?= ($course->status->value ?? '') === 'active' ? 'selected' : '' ?>>Attivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="durationInHours" class="form-label">Durata in ore</label>
                <input type="number" class="form-control" id="durationInHours" name="durationInHours" required
                    value="<?= $course->durationInHours ?? '' ?>">
            </div>
            <div class="mb-3">
                <label for="maxCapacity" class="form-label">Capienza massima</label>
                <input type="number" class="form-control" id="maxCapacity" name="maxCapacity" required
                    value="<?= $course->maxCapacity ?? '' ?>">
            </div>
            <div class="mb-3">
                <input type="checkbox" id="isMandatory" name="isMandatory" value="1"
                    <?= ($course->isMandatory ?? false) ? 'checked' : '' ?>>
                <label for="isMandatory">Partecipazione obbligatoria</label>
            </div>
            <button type="submit" class="btn btn-success my-3">Salva Corso</button>
        </form>
    </div>

</body>

</html>
