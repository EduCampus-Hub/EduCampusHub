<?php

use App\Presentation\Routing\CourseRoutes;

?>

<div class="form-page">
    <a class="back-link" href="<?= CourseRoutes::INDEX ?>">&larr; Tutti i corsi</a>
    <div class="page-heading">
        <h1><?= $escape($pageTitle) ?></h1>
    </div>

    <form class="surface course-form" method="post" action="<?= $escape($action) ?>">
        <?php if ($errorMessage !== null): ?>
            <p class="form-error" role="alert"><?= $escape($errorMessage) ?></p>
        <?php endif; ?>

        <div class="form-grid">
            <div class="field field-full">
                <label for="title">Titolo</label>
                <input id="title" name="title" required value="<?= $escape($values['title']) ?>">
            </div>
            <div class="field field-full">
                <label for="description">Descrizione</label>
                <textarea id="description" name="description" rows="5" required><?= $escape($values['description']) ?></textarea>
            </div>
            <div class="field">
                <label for="targetAudience">Destinatari</label>
                <select id="targetAudience" name="targetAudience" required>
                    <?php foreach ($targetLabels as $value => $label): ?>
                        <option value="<?= $escape($value) ?>" <?= $values['targetAudience'] === $value ? 'selected' : '' ?>>
                            <?= $escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="status">Stato</label>
                <select id="status" name="status" required>
                    <?php foreach ($statusLabels as $value => $label): ?>
                        <option value="<?= $escape($value) ?>" <?= $values['status'] === $value ? 'selected' : '' ?>>
                            <?= $escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="durationInHours">Durata in ore</label>
                <input id="durationInHours" name="durationInHours" type="number" min="1" required
                       value="<?= $escape($values['durationInHours']) ?>">
            </div>
            <div class="field">
                <label for="maxCapacity">Capienza massima</label>
                <input id="maxCapacity" name="maxCapacity" type="number" min="1" required
                       value="<?= $escape($values['maxCapacity']) ?>">
            </div>
            <label class="checkbox field-full" for="isMandatory">
                <input type="checkbox" id="isMandatory" name="isMandatory" value="1" <?= $values['isMandatory'] ? 'checked' : '' ?>>
                Partecipazione obbligatoria
            </label>
        </div>

        <div class="form-actions">
            <a class="button button-secondary" href="<?= CourseRoutes::INDEX ?>">Annulla</a>
            <button class="button" type="submit"><?= $isEdit ? 'Salva modifiche' : 'Crea corso' ?></button>
        </div>
    </form>
</div>
