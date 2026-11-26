<?php
// Values supplied by the controller.
$totalCourses = $totalCourses ?? 0;
$registeredUsers = $registeredUsers ?? 0;
$totalEnrollments = $totalEnrollments ?? 0;
$issuedCertificates = $issuedCertificates ?? 0;
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'home';
$courses = $courses ?? [];
$enrollments = $enrollments ?? [];
$users = $users ?? [];

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
    <title>Dashboard amministrativa</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<main class="dashboard">
    <header class="header">
        <div>
            <h1>Dashboard amministrativa</h1>
            <p>Gestione corsi, utenti, iscrizioni e attestati.</p>
        </div>
        <a class="btn" href="index.php?action=create_course">+ Nuovo corso</a>
    </header>

    <section aria-labelledby="kpi-title">
        <h2 id="kpi-title">Indicatori principali</h2>
        <div class="kpi-grid">
            <article class="kpi-card">
                <h2>Totale corsi</h2>
                <strong><?= $escape($totalCourses) ?></strong>
            </article>

            <article class="kpi-card">
                <h2>Utenti registrati</h2>
                <strong><?= $escape($registeredUsers) ?></strong>
            </article>

            <article class="kpi-card">
                <h2>Totale iscrizioni</h2>
                <strong><?= $escape($totalEnrollments) ?></strong>
            </article>

            <article class="kpi-card">
                <h2>Attestati emessi</h2>
                <strong><?= $escape($issuedCertificates) ?></strong>
            </article>
        </div>
    </section>

    <section class="panel" aria-labelledby="courses-title">
        <div class="panel-header">
            <h2 id="courses-title">Gestione corsi</h2>
            <a class="btn" href="index.php?action=create_course">Crea corso</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Ore</th>
                        <th>Target</th>
                        <th>Capienza</th>
                        <th>Categorie attive</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td class="empty" colspan="7">Nessun corso presente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <?php
                            $id = $getValue($course, 'id');
                            $status = strtolower((string) $getValue($course, 'stato', 'bozza'));
                            $categories = $getValue($course, 'categorie', []);
                            if (is_array($categories)) {
                                $categoriesText = implode(', ', $categories);
                            } else {
                                $categoriesText = (string) $categories;
                            }
                            ?>
                            <tr>
                                <td><?= $escape($getValue($course, 'titolo', 'Senza titolo')) ?></td>
                                <td><?= $escape($getValue($course, 'ore', '')) ?></td>
                                <td><?= $escape($getValue($course, 'target', '')) ?></td>
                                <td><?= $escape($getValue($course, 'capienza', '')) ?></td>
                                <td><?= $escape($categoriesText !== '' ? $categoriesText : 'Non selezionate') ?></td>
                                <td>
                                    <span class="badge <?= $status === 'attivo' ? 'attivo' : 'bozza' ?>">
                                        <?= $escape(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-secondary" href="index.php?action=edit_course&amp;id=<?= $escape($id) ?>">Modifica</a>
                                        <a class="btn btn-secondary" href="index.php?action=edit_course&amp;id=<?= $escape($id) ?>&amp;toggle_status=1">
                                            <?= $status === 'attivo' ? 'Metti in bozza' : 'Attiva' ?>
                                        </a>
                                        <a class="btn btn-danger" href="index.php?action=delete_course&amp;id=<?= $escape($id) ?>" onclick="return confirm('Eliminare questo corso?');">Elimina</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel" aria-labelledby="registrations-title">
        <div class="panel-header">
            <h2 id="registrations-title">Iscrizioni e attestati</h2>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Utente</th>
                        <th>Corso</th>
                        <th>Data iscrizione</th>
                        <th>Attestato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enrollments)): ?>
                        <tr>
                            <td class="empty" colspan="5">Nessuna iscrizione presente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enrollments as $enrollment): ?>
                            <?php
                            $id = $getValue($enrollment, 'id');
                            $certificate = $getValue($enrollment, 'attestato', '');
                            ?>
                            <tr>
                                <td><?= $escape($getValue($enrollment, 'utente', '')) ?></td>
                                <td><?= $escape($getValue($enrollment, 'corso', '')) ?></td>
                                <td><?= $escape($getValue($enrollment, 'data', '')) ?></td>
                                <td><?= $escape($certificate !== '' ? $certificate : 'Non disponibile') ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-secondary" href="index.php?action=download_certificate&amp;id=<?= $escape($id) ?>">Scarica</a>
                                        <a class="btn" href="index.php?action=upload_certificate&amp;id=<?= $escape($id) ?>">Carica firmato</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel" aria-labelledby="users-title">
        <div class="panel-header">
            <h2 id="users-title">Anagrafica utenti</h2>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Classe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td class="empty" colspan="5">Nessun utente presente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $escape($getValue($user, 'nome', '')) ?></td>
                                <td><?= $escape($getValue($user, 'cognome', '')) ?></td>
                                <td><?= $escape($getValue($user, 'email', '')) ?></td>
                                <td><?= $escape($getValue($user, 'ruolo', '')) ?></td>
                                <td><?= $escape($getValue($user, 'classe', '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php if (in_array(($action ?? ''), ['create_course', 'edit_course'], true)): ?>
        <section class="panel" aria-labelledby="course-form-title">
            <div class="panel-header">
                <h2 id="course-form-title"><?= ($action ?? '') === 'create_course' ? 'Crea nuovo corso' : 'Modifica corso' ?></h2>
            </div>

            <form class="course-form" method="post" action="index.php?action=<?= $escape($action) ?>">
                <?php if (($action ?? '') === 'edit_course'): ?>
                    <input type="hidden" name="id" value="<?= $escape($_GET['id'] ?? '') ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <label>Titolo<input type="text" name="titolo" required></label>
                    <label>Ore<input type="number" name="ore" min="1" required></label>
                    <label>Target<input type="text" name="target" required></label>
                    <label>Capienza<input type="number" name="capienza" min="1" required></label>
                    <label>Stato
                        <select name="stato">
                            <option value="bozza">Bozza</option>
                            <option value="attivo">Attivo</option>
                        </select>
                    </label>
                    <label>Categorie visibili
                        <select name="categorie[]" multiple>
                            <option value="Studenti">Studenti</option>
                            <option value="Docenti">Docenti</option>
                            <option value="ATA">ATA</option>
                            <option value="Tutti">Tutti</option>
                        </select>
                    </label>
                </div>

                <div class="form-actions">
                    <button class="btn" type="submit">Salva corso</button>
                    <a class="btn btn-secondary" href="index.php?action=home">Annulla</a>
                </div>
            </form>
        </section>
    <?php endif; ?>
</main>

</body>
</html>

