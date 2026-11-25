<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deash Board Studente</title>
    <!-- Collegamento al file CSS esterno -->
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <!--   int $maxCapacity,
        string $targetAudience,
        string $status,
        public string $id,
        string $title,
        string $description,
        bool $isMandatory,
        int $durationInHours -->

<div class="container">
    <div class="header-bar">
        <h1>📋 Lista Corsi</h1>
        <a href="/List-Teachers" class="btn">Docenti</a>
        <a href="/List-Ata" class="btn">Ata</a>
        <a href="/Certificate" class="btn">Certificato</a>

    </div>

    <?php if (empty($corsi)): ?>
        <p>Nessun corso presente.</p>
    <?php else: ?>
        <?php foreach ($corsi as $corso): ?>
            <div class="card">
                <h3><?= htmlspecialchars($corso->title) ?></h3>
                <p><?= htmlspecialchars($corso->description) ?></p>
                <div class="meta">
                    <strong>Totale Durata:</strong> <?= htmlspecialchars($corso->durationInHours) ?> | 
                    <strong>Capienza Massima:</strong> <?= htmlspecialchars($corso->maxCapacity) ?>
                </div>
                <a href="/Visualizza?id=<?php urlencode($corso->id); ?>" class="btn">Visualizza maggiori Informazioni</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>