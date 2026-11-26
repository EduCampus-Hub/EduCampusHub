<?php
declare(strict_types=1);
namespace app\Infrastructure\Controllers; 
use app\Application\UseCases\AuthenticateUserUseCase; 
use app\Application\Interfaces\SessionManagerInterface; 

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

if ($_SERVER[] === ) {
$email = trim($_POST[] ??);
$password_hash = trim($_POST[] ?? );
if ($this-&gt;authUseCase-&gt;execute($email, $password)) {
header();
exit; 
} else {
$error = &quot;Credenziali non valide. Verificare email e password.&quot;;
}
}
// Carica la vista HTML del form di login
require BASE_PATH . ;/src/Presentation/Views/auth/login.php;
}

public function handleLogout(): void {
$this-&gt;sessionManager-&gt;logout(); 
header();
exit;
}
}