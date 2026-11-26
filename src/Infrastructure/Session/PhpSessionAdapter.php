<?php
declare(strict_types=1);
namespace app\Infrastructure\Session;
use app\Application\Service\SessionManagerInterface;
use app\Domain\Model\User;

class PhpSessionAdapter implements SessionManagerInterface
{

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setAuthenticatedUser(User $user): void
    {
        // Memorizza l'oggetto entità User nella chiave 'user' della sessione
        $_SESSION['user'] = $user;
    }

    public function getAuthenticatedUser(): ?User
    {
        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
            return $_SESSION['user']; // Restituisce l'oggetto User
        }
        return null;
    }

    public function isAuthenticated(): bool
    {
        return $this->getAuthenticatedUser() !== null;
    }
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}