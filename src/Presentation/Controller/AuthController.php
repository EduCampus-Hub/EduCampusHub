<?php
declare(strict_types=1);
namespace app\Presentation\Controller;

use app\Application\DTO\LoginDTO;
use app\Application\UseCase\AuthenticateUserUseCase;
use app\Application\Service\SessionManagerInterface;

/**
 * Controller per la gestione delle azioni di autenticazione (login/logout).
 */
class AuthController
{
    public function __construct(
        private AuthenticateUserUseCase $authUseCase,
        private SessionManagerInterface $sessionManager
    ) {
    }

    public function handleLogin(): void
    {
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            $dto = new LoginDTO($email, $password);
            $user = $this->authUseCase->execute($dto);

            if ($user !== null) {
                header('Location: /index.php?action=home');
                exit;
            }

            $error = 'Credenziali non valide. Verificare email e password.';
        }

        require dirname(__DIR__, 3) . '/src/Presentation/Views/login.php';
    }

    public function handleLogout(): void
    {
        $this->sessionManager->logout();
        header('Location: /index.php?action=login');
        exit;
    }
}