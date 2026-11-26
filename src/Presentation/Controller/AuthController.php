<?php
declare(strict_types=1);
namespace app\Presentation\Controllers; // Layer Infrastructure
use app\Application\UseCases\AuthenticateUserUseCase; // Iniezione Use Case Login
use app\Application\Service\SessionManagerInterface; // Iniezione Gestore Sessione
/**
* Controller per la gestione delle azioni di Autenticazione (Login/Logout).
*/
class AuthController {
public function __construct(
private AuthenticateUserUseCase $authUseCase,
private SessionManagerInterface $sessionManager
) {}
/**
* Gestisce la richiesta di Login (Visualizzazione form ed elaborazione POST).
*/
public function handleLogin(): void {
$error = null;
// Se l'utente ha inviato il modulo di Login tramite metodo HTTP POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email'] ?? '');


$password = trim($_POST['password'] ?? '');
// Esegue lo Use Case di Autenticazione
if ($this->authUseCase->execute($email, $password)) {
header('Location: index.php?action=home');
exit; // FONDAMENTALE: blocca l'esecuzione dello script dopo il redirect!
} else {
$error = "Credenziali non valide. Verificare email e password.";
}
}
// Carica la vista HTML del form di login
require BASE_PATH . '/src/Presentation/Views/login.php';
}
/**
* Gestisce l'azione di Logout dell'utente.
*/
public function handleLogout(): void {
$this->sessionManager->logout(); // Invocherà il metodo dell'adattatore
header('Location: index.php?action=login'); // Reindirizza al form di login
exit;
}
}