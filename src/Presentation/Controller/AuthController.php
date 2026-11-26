<?php
declare(strict_types=1);
namespace app\Presentation\Controller;

use app\Application\DTO\LoginDTO;
use app\Application\DTO\RegisterUserDTO;
use app\Application\UseCase\AuthenticateUserUseCase;
use app\Application\UseCase\RegisterUserUseCase;
use app\Application\Service\SessionManagerInterface;
use app\Domain\Enum\UserRoleEnum;
use InvalidArgumentException;
use RuntimeException;

/**
 * Controller per la gestione delle azioni di autenticazione (login/logout).
 */
class AuthController
{
    public function __construct(
        private AuthenticateUserUseCase $authUseCase,
        private RegisterUserUseCase $registerUserUseCase,
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

    public function handleRegister(): void
    {
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            try {
                $role = UserRoleEnum::from((string) ($_POST['role'] ?? 'STUDENTE'));
                $dto = new RegisterUserDTO('', $name, $email, $password, $role, null);

                $this->registerUserUseCase->execute($dto);
                header('Location: /index.php?action=login');
                exit;
            } catch (InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (RuntimeException $e) {
                $error = 'Si è verificato un errore durante la registrazione. Riprova più tardi.';
            } catch (\ValueError) {
                $error = 'Seleziona un ruolo valido.';
            }
        }

        require dirname(__DIR__, 3) . '/src/Presentation/Views/register.php';
    }
}