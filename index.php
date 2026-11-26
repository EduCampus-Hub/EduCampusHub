<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$action = isset($_GET['action']) ? (string) $_GET['action'] : 'home';
$role = isset($_GET['role']) ? strtolower((string) $_GET['role']) : 'admin';

$totalCourses = 0;
$registeredUsers = 0;
$totalEnrollments = 0;
$issuedCertificates = 0;
$courses = [];
$enrollments = [];
$users = [];

switch ($action) {
    case 'home':
        if ($role === 'student' || $role === 'studente') {
            require __DIR__ . '/src/Presentation/Views/DashboardStudent.php';
        } else {
            require __DIR__ . '/src/Presentation/Views/DashboardAdmin.php';
        }
        break;

    case 'create_course':
    case 'edit_course':
        require __DIR__ . '/src/Presentation/Views/DashboardAdmin.php';
        break;

    case 'download_certificate':
        http_response_code(501);
        echo '<h1>Attestato PDF non ancora disponibile</h1>';
        break;

    case 'upload_certificate':
        http_response_code(501);
        echo '<h1>Caricamento attestato non ancora disponibile</h1>';
        break;

    default:
        http_response_code(404);
        echo '<h1>404 - Pagina non trovata</h1>';
}
