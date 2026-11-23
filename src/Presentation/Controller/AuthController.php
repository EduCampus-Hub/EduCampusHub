<?php
declare(strict_types=1);
namespace App\Infrastructure\Controllers; // Layer Infrastructure
use App\Application\UseCases\AuthenticateUserUseCase; // Iniezione Use Case Login
use App\Application\Interfaces\SessionManagerInterface; // Iniezione Gestore Sessione

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
// Se l&#39;utente ha inviato il modulo di Login tramite metodo HTTP POST
if ($_SERVER[] === ) {
$email = trim($_POST[] ??);
$password = trim($_POST[] ?? );
// Esegue lo Use Case di Autenticazione
if ($this-&gt;authUseCase-&gt;execute($email, $password)) {
// Login riuscito: reindirizza alla home page dell&#39;agenda
header();
exit; // FONDAMENTALE: blocca l&#39;esecuzione dello script dopo il redirect!
} else {
$error = &quot;Credenziali non valide. Verificare email e password.&quot;;
}
}
// Carica la vista HTML del form di login
require BASE_PATH . ;/src/Presentation/Views/auth/login.php;
}
/**
* Gestisce l&#39;azione di Logout dell&#39;utente.
*/
public function handleLogout(): void {
$this-&gt;sessionManager-&gt;logout(); // Invocherà il metodo dell&#39;adattatore
header(); // Reindirizza al form di login
exit;
}
}