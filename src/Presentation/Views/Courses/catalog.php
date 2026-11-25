<?php

use App\Presentation\Routing\CourseRoutes;

?>

<div class="page-heading">
    <h1>Corsi</h1>
    <a class="button" href="<?= CourseRoutes::CREATE ?>">Nuovo corso</a>
</div>

<?php if ($courses === []): ?>
    <p class="surface empty-state">Non ci sono ancora corsi.</p>
<?php else: ?>
    <div class="course-list">
        <?php foreach ($courses as $course): ?>
            <article class="surface course-card">
                <div class="course-heading">
                    <h2><?= $escape($course->title) ?></h2>
                    <span class="status status-<?= $escape($course->status->value) ?>">
                        <?= $escape($statusLabels[$course->status->value]) ?>
                    </span>
                </div>
                <p class="description"><?= $escape($course->description) ?></p>
                <div class="course-footer">
                    <dl class="course-details">
                        <div><dt>Destinatari</dt><dd><?= $escape($targetLabels[$course->target->value]) ?></dd></div>
                        <div><dt>Durata</dt><dd><?= (int) $course->durationInHours ?> h</dd></div>
                        <div><dt>Capienza</dt><dd><?= (int) $course->maxCapacity ?> posti</dd></div>
                        <div><dt>Partecipazione</dt><dd><?= $course->isMandatory ? 'Obbligatoria' : 'Facoltativa' ?></dd></div>
                    </dl>
                    <a class="button button-secondary" href="<?= $escape(CourseRoutes::editPath($course->id)) ?>"
                       aria-label="Modifica <?= $escape($course->title) ?>">Modifica</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
