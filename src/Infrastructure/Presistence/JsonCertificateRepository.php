<?php

declare(strict_types=1);

namespace App\Infrastructure\Presistence;

use App\Domain\Model\Certificate;
use App\Domain\Repository\CertificateRepositoryInterface;
use InvalidArgumentException;
use RuntimeException;

class JsonCertificateRepository implements CertificateRepositoryInterface
{
    public function __construct(
        private string $filePath
    ) {}

    public function findById(string $id): ?Certificate
    {
        $this->validateId($id, 'ID certificato');

        foreach ($this->findAll() as $certificate) {
            if ($certificate->id === $id) {
                return $certificate;
            }
        }

        return null;
    }

    public function findByEnrollment(string $EnrollmentId): ?Certificate
    {
        $this->validateId($EnrollmentId, 'ID iscrizione');

        foreach ($this->findAll() as $certificate) {
            if ($certificate->enrollmentId === $EnrollmentId) {
                return $certificate;
            }
        }

        return null;
    }

    /**
     * @return Certificate[]
     */
    public function findByUser(string $userId): array
    {
        $this->validateId($userId, 'ID utente');

        return array_values(array_filter(
            $this->findAll(),
            fn (Certificate $certificate): bool => $certificate->userId === $userId
        ));
    }

    public function save(Certificate $certificate): bool
    {
        $existingCertificates = file_exists($this->filePath) ? $this->findAll() : [];
        $certificates = array_values(array_filter(
            $existingCertificates,
            fn (Certificate $existing): bool => $existing->id !== $certificate->id
        ));
        $certificates[] = $certificate;

        $data = array_map(static function (Certificate $item): array {
            return [
                'id' => $item->id,
                'enrollmentId' => $item->enrollmentId,
                'userId' => $item->userId,
                'coursedId' => $item->coursedId,
                'issuedAt' => $item->issuedAt,
                'certificateCode' => $item->certificateCode,
            ];
        }, $certificates);

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        // Gli errori di scrittura vengono segnalati al chiamante restituendo false.
        return @file_put_contents($this->filePath, $json, LOCK_EX) !== false;
    }

    /**
     * @return Certificate[]
     */
    private function findAll(): array
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Impossibile trovare il file dei dati: {$this->filePath}");
        }

        $content = @file_get_contents($this->filePath);
        if ($content === false) {
            throw new RuntimeException("Impossibile leggere il file dei dati: {$this->filePath}");
        }

        $content = trim($content);
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            throw new RuntimeException("Il file non e' valido o e' vuoto.");
        }

        $certificates = [];
        foreach ($data as $item) {
            if (
                !is_array($item)
                || !isset(
                    $item['id'],
                    $item['enrollmentId'],
                    $item['userId'],
                    $item['coursedId'],
                    $item['issuedAt'],
                    $item['certificateCode']
                )
            ) {
                continue;
            }

            $certificates[] = Certificate::restore(
                id: (string) $item['id'],
                enrollmentId: (string) $item['enrollmentId'],
                userId: (string) $item['userId'],
                coursedId: (string) $item['coursedId'],
                issuedAt: (string) $item['issuedAt'],
                certificateCode: (string) $item['certificateCode']
            );
        }

        return $certificates;
    }

    private function validateId(string $id, string $label): void
    {
        if (preg_match('/\A[0-9]{4}(?:-[0-9]{4}){3}\z/', $id) !== 1) {
            throw new InvalidArgumentException("{$label} non valido: formato richiesto 0000-0000-0000-0000.");
        }
    }
}
