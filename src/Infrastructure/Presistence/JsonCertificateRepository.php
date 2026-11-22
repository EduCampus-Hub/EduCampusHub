<?php
//Impedisce a PHP di convertire in maniera automatica i tipi di dato
declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Certificate;
use App\Domain\CertificateRepositoryInterface;
use RuntimeException;

class JsonCertificateRepository implements CertificateRepositoryInterface
{
    public function __construct(
        private string $filePath
    ) {}

    /**
     * @return Certificate[]
     */
    public function findById(int $id ): ?Certificate
    {
        if($id <= 0){
                throw new InvalidArgumentException ("l'id deve essere un numero maggiore di 0");
        }

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = file_get_contents($this->filePath);
        // Rimuove eventuali caratteri UTF-8 BOM o spazi invisibili
        $content = trim($content);
        $data = json_decode($content, true);

        if (!is_array($data)) {
         
        throw new RuntimeException("Il file non e' valido o e' vuoto.");
        }

        $certificates = [];
        foreach ($data as $item) {
            // Verifica che l'elemento sia un array con tutte le chiavi presenti
            if (
                is_array($item) && 
                isset($item['id'], $item['enrollmentId'], $item['userId'], $item['coursedId'], $item['issuedAt'])
            ) {
                $certificates[] = Certificate::restore(
                    id: (int) $item['id'],
                    enrollmentId: (string) $item['enrollmentId'],
                    userId: (string) $item['userId'],
                    coursedId: (string) $item['coursedId'],
                    issuedAt: (string) $item['issuedAt']
                );
            }
        }

        return array_find($certificates, fn(Certificate $c): bool => $c->id === $id);
        
    }

    public function findByEnrollment(string $EnrollmentId): ?Certificate
    {
        
        if(trim($EnrollmentId ?? '') === ''){
                throw new InvalidArgumentException ("ID iscrizione va inserito");
        }

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = file_get_contents($this->filePath);
        // Rimuove eventuali caratteri UTF-8 BOM o spazi invisibili
        $content = trim($content);
        $data = json_decode($content, true);

        if (!is_array($data)) {
         
        throw new RuntimeException("Il file non e' valido o e' vuoto.");
        }

        $requests = [];
        foreach ($data as $item) {
            // Verifica che l'elemento sia un array con tutte le chiavi presenti
            if (
                is_array($item) && 
                isset($item['id'], $item['enrollmentId'], $item['userId'], $item['coursedId'], $item['issuedAt'])
            ) {
                $requests[] = Certificate::restore(
                    id: (int) $item['id'],
                    enrollmentId: (string) $item['enrollmentId'],
                    userId: (string) $item['userId'],
                    coursedId: (string) $item['coursedId'],
                    issuedAt: (string) $item['issuedAt']
                );
            }
        }

        return array_find($requests, fn(Certificate $c): bool => $c->enrollmentId === $EnrollmentId);
    }

    public function findByUser(string $userId): ?User
    {
        /* da usare in caso l'ID passato come parametro deve esser eun intero
        if ($userId <= 0) {
        throw new InvalidArgumentException("ID utente deve essere maggiore di 0");
    }
        */

        //controllo sull'ID se è stringa, lo prova a convertire in un intero e verifica che sia maggiore di 0 
        $idValue = filter_var($userId, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
        ]);

        if ($idValue === false) {
        throw new InvalidArgumentException("ID utente non valido, deve essere un numero maggiore di 0");
    }

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = file_get_contents($this->filePath);
        // Rimuove eventuali caratteri UTF-8 BOM o spazi invisibili
        $content = trim($content);
        $data = json_decode($content, true);

        if (!is_array($data)) {
         
        throw new RuntimeException("Il file non e' valido o e' vuoto.");
        }

        $users = [];
        foreach ($data as $item) {
            // Verifica che l'elemento sia un array con tutte le chiavi presenti
            if (
                is_array($item) && 
                isset($item['id'], $item['name'], $item['email'], $item['password_hash'], $item['role']) && array_key_exists('user_class', $item)
            ) {
                $users[] = User::restore(
                    id: (int) $item['id'],
                    name: (string) $item['name'],
                    email: (string) $item['email'],
                    password_hash: (string) $item['password_hash'],
                    role: UserRoleEnum::from((string) $item['role']),
                    user_class: $item['user_class'] !== null ? (string) $item['user_class'] : null

                );
            }
        }

        return array_find($users, fn(User $u): bool => $u->id === $idValue);
    }

    public function save(Certificate $certificate): bool
    {
        $existingCertificates= $this->findAll();

        $certificates = array_filter(
        $existingCertificates,
        fn(Certificate $c): bool => $c->id !== $certificate->id
    );

    $certificatesToSave[] = $certificate;

        // Convertiamo le entità Certificate in array associativi per il JSON
        $data = array_map(function (Certificate $req) {
            return [
                'id' => $req->id,
                'enrollmentId' => $req->enrollmentId,
                'userId' => $req->userId,
                'coursedId' => $req->coursedId,
                'issuedAt' => $req->issuedAt,
            ];
        }, $certificates);

        // Aggiungiamo il nuovo certificato
        $data[] = [
            'id' => $certificate->id,
            'enrollmentId' => $certificate->enrollmentId,
            'userId' => $certificate->userId,
            'coursedId' => $certificate->coursedId,
            'issuedAt' => $certificate->issuedAt,
        ];

        // Salviamo sul file JSON con la formattazione pulita
        file_put_contents(
            $this->filePath, 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return file_put_contents($this->filePath, $json, LOCK_EX) !== false;
    }
}