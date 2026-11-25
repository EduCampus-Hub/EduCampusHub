<?php

use App\Presentation\Routing\CourseRoutes;

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$targetLabels = [
    'all' => 'Tutti',
    'students' => 'Studenti',
    'teachers' => 'Docenti',
    'atc' => 'Personale ATC',
];
$statusLabels = ['draft' => 'Bozza', 'active' => 'Attivo'];
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($pageTitle) ?> · EduCampus Hub</title>
    <link rel="stylesheet" href="/public/css/courses.css">
</head>
<body>
    <header class="site-header">
        <div class="site-width site-header-inner">
            <a class="brand" href="<?= CourseRoutes::INDEX ?>">EduCampus <span>Hub</span></a>
        </div>
    </header>
    <main class="site-width content">
        <?php require __DIR__ . '/../Courses/' . $view; ?>
    </main>
</body>
</html>
