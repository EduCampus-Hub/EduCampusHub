<?php

namespace app\Infrastructure\Persistence;

use app\Domain\Enum\UserRoleEnum;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use RuntimeException;

class JsonUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private string $filePath
    ) {}

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = file_get_contents($this->filePath);
        // Rimuove eventuali spazi invisibili
        $content = trim($content);
        $data = json_decode($content, true);

        if ($data === null) {
            return [];
        }

        $users = [];
        foreach ($data as $item) {
            // Verifica che l'elemento sia un array con tutte le chiavi obbligatorie presenti
            if (
                is_array($item) &&
                isset($item['id'], $item['name'], $item['email'], $item['password_hash'], $item['role'])
            ) {
                $users[] = new User(
                    id: (string) $item['id'],
                    name: (string) $item['name'],
                    email: (string) $item['email'],
                    password_hash: (string) $item['password_hash'],
                    role: UserRoleEnum::from($item['role']),
                    user_class: isset($item['user_class']) ? (string) $item['user_class'] : null
                );
            }
        }

        return $users;
    }

    public function findById(string $id): ?User
    {
        foreach ($this->findAll() as $user) {
            if ($user->id === $id) {
                return $user;
            }
        }

        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $email = trim($email);

        foreach ($this->findAll() as $user) {
            if (strcasecmp($user->email, $email) === 0) {
                return $user;
            }
        }

        return null;
    }

    public function save(User $user): bool
    {
        $users = $this->findAll();

        // Convertiamo le entità User in array associativi per il JSON
        $data = array_map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'password_hash' => $u->password_hash,
                'role' => $u->role->value,
                'user_class' => $u->user_class,
            ];
        }, $users);

        // Aggiungiamo il nuovo utente
        $data[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password_hash' => $user->password_hash,
            'role' => $user->role->value,
            'user_class' => $user->user_class,
        ];

        // Salviamo sul file JSON con la formattazione pulita
        $result = file_put_contents(
            $this->filePath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $result !== false;
    }
}