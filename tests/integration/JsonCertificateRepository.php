<?php

declare(strict_types=1);

namespace tests\integration;

use App\Domain\Model\Certificate;
use App\Infrastructure\Presistence\JsonCertificateRepository;
use JsonException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class JsonCertificateRepositoryTest extends TestCase
{
    private const CERTIFICATE_ID = '0000-0000-0000-0001';
    private const SECOND_CERTIFICATE_ID = '0000-0000-0000-0002';
    private const THIRD_CERTIFICATE_ID = '0000-0000-0000-0003';
    private const ENROLLMENT_ID = '1000-0000-0000-0001';
    private const SECOND_ENROLLMENT_ID = '1000-0000-0000-0002';
    private const THIRD_ENROLLMENT_ID = '1000-0000-0000-0003';
    private const UPDATED_ENROLLMENT_ID = '1000-0000-0000-0004';
    private const USER_ID = '0000-0000-0000-0010';
    private const OTHER_USER_ID = '0000-0000-0000-0020';
    private const COURSE_ID = '2000-0000-0000-0001';
    private const UPDATED_COURSE_ID = '2000-0000-0000-0002';
    private const MISSING_ID = '9999-9999-9999-9999';

    private string $filePath;
    private JsonCertificateRepository $repository;

    protected function setUp(): void
    {
        // Ogni test usa un file temporaneo, senza modificare i dati applicativi.
        $filePath = tempnam(sys_get_temp_dir(), 'certificates_test_');
        if ($filePath === false) {
            throw new RuntimeException('Impossibile creare il file temporaneo per il test.');
        }

        $this->filePath = $filePath;
        $this->writeCertificates([]);
        $this->repository = new JsonCertificateRepository($this->filePath);
    }

    protected function tearDown(): void
    {
        if (isset($this->filePath) && file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testFindByIdRestoresAllCertificateFields(): void
    {
        $expected = $this->certificateData();
        $this->writeCertificates([$this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID), $expected]);

        self::assertEquals($this->restoreCertificate($expected), $this->repository->findById(self::CERTIFICATE_ID));
    }

    public function testFindByEnrollmentRestoresAllCertificateFields(): void
    {
        $expected = $this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID);
        $this->writeCertificates([$this->certificateData(), $expected]);

        self::assertEquals(
            $this->restoreCertificate($expected),
            $this->repository->findByEnrollment(self::SECOND_ENROLLMENT_ID)
        );
    }

    public function testFindByUserReturnsOnlyCertificatesBelongingToTheUser(): void
    {
        $otherUser = array_replace($this->certificateData(), ['userId' => self::OTHER_USER_ID]);
        $first = $this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID);
        $second = $this->certificateData(self::THIRD_CERTIFICATE_ID, self::THIRD_ENROLLMENT_ID);
        $this->writeCertificates([$otherUser, $first, $second]);

        self::assertEquals(
            [$this->restoreCertificate($first), $this->restoreCertificate($second)],
            $this->repository->findByUser(self::USER_ID)
        );
    }

    public function testFindByUserReturnsAnEmptyListWhenUserHasNoCertificates(): void
    {
        $this->writeCertificates([$this->certificateData()]);

        self::assertSame([], $this->repository->findByUser(self::MISSING_ID));
    }

    public function testFindByUserReturnsAnEmptyListForAnEmptyCollection(): void
    {
        self::assertSame([], $this->repository->findByUser(self::USER_ID));
    }

    #[DataProvider('lookupProvider')]
    public function testLookupReturnsNullWhenCertificateDoesNotExist(string $method, string $value): void
    {
        $this->writeCertificates([$this->certificateData()]);

        self::assertNull($this->repository->$method($value));
    }

    #[DataProvider('lookupProvider')]
    public function testLookupReturnsNullForAnEmptyCollection(string $method, string $value): void
    {
        self::assertNull($this->repository->$method($value));
    }

    public static function lookupProvider(): iterable
    {
        yield 'id' => ['findById', self::MISSING_ID];
        yield 'iscrizione' => ['findByEnrollment', self::MISSING_ID];
    }

    #[DataProvider('invalidIdProvider')]
    public function testFindByIdRejectsInvalidIds(string $id): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID certificato non valido: formato richiesto 0000-0000-0000-0000.');

        $this->repository->findById($id);
    }

    public static function invalidIdProvider(): iterable
    {
        yield 'vuoto' => [''];
        yield 'spazi' => ['   '];
        yield 'zero senza formato' => ['0'];
        yield 'intero senza formato' => ['1'];
        yield 'negativo' => ['-1'];
        yield 'testo' => ['abc'];
        yield 'decimale' => ['1.5'];
        yield 'separatori mancanti' => ['1234567890123456'];
        yield 'gruppo mancante' => ['1234-5678-9012'];
        yield 'gruppo aggiuntivo' => ['1234-5678-9012-3456-7890'];
        yield 'gruppo troppo corto' => ['123-5678-9012-3456'];
        yield 'gruppo troppo lungo' => ['12345-5678-9012-3456'];
        yield 'lettera al posto di cifra' => ['1234-5678-9012-345a'];
        yield 'cifre non ASCII' => ['１２３４-5678-9012-3456'];
        yield 'spazio iniziale' => [' ' . self::CERTIFICATE_ID];
        yield 'spazio finale' => [self::CERTIFICATE_ID . ' '];
        yield 'a capo finale' => [self::CERTIFICATE_ID . "\n"];
    }

    #[DataProvider('invalidEnrollmentIdProvider')]
    public function testFindByEnrollmentRejectsInvalidIds(string $enrollmentId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID iscrizione non valido: formato richiesto 0000-0000-0000-0000.');

        $this->repository->findByEnrollment($enrollmentId);
    }

    public static function invalidEnrollmentIdProvider(): iterable
    {
        yield from self::invalidIdProvider();
    }

    #[DataProvider('invalidUserIdProvider')]
    public function testFindByUserRejectsInvalidIds(string $userId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID utente non valido: formato richiesto 0000-0000-0000-0000.');

        $this->repository->findByUser($userId);
    }

    public static function invalidUserIdProvider(): iterable
    {
        yield from self::invalidIdProvider();
    }

    #[DataProvider('fileLookupProvider')]
    public function testLookupThrowsWhenFileDoesNotExist(string $method, string $value): void
    {
        unlink($this->filePath);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Impossibile trovare il file dei dati: {$this->filePath}");

        $this->repository->$method($value);
    }

    public static function fileLookupProvider(): iterable
    {
        yield 'id' => ['findById', self::CERTIFICATE_ID];
        yield 'iscrizione' => ['findByEnrollment', self::ENROLLMENT_ID];
        yield 'utente' => ['findByUser', self::USER_ID];
    }

    #[DataProvider('invalidFileContentProvider')]
    public function testLookupRejectsInvalidFileContent(string $method, string $value, string $content): void
    {
        file_put_contents($this->filePath, $content);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Il file non e' valido o e' vuoto.");

        $this->repository->$method($value);
    }

    public static function invalidFileContentProvider(): iterable
    {
        $contents = [
            'file vuoto' => '',
            'solo spazi' => " \n\t ",
            'json malformato' => '{invalid json',
            'null' => 'null',
            'stringa' => '"certificate"',
            'numero' => '42',
            'booleano' => 'false',
        ];

        foreach (self::fileLookupProvider() as $lookup => [$method, $value]) {
            foreach ($contents as $description => $content) {
                yield "$lookup: $description" => [$method, $value, $content];
            }
        }
    }

    public function testLookupsAcceptWhitespaceAroundJson(): void
    {
        $this->writeCertificates([$this->certificateData()]);
        $content = file_get_contents($this->filePath);
        self::assertIsString($content);
        file_put_contents($this->filePath, " \n\t" . $content . "\n ");

        $expected = $this->restoreCertificate($this->certificateData());
        self::assertEquals($expected, $this->repository->findById(self::CERTIFICATE_ID));
        self::assertEquals($expected, $this->repository->findByEnrollment(self::ENROLLMENT_ID));
        self::assertEquals([$expected], $this->repository->findByUser(self::USER_ID));
    }

    public function testLookupsAcceptUtf8Bom(): void
    {
        $this->writeCertificates([$this->certificateData()]);
        $content = file_get_contents($this->filePath);
        self::assertIsString($content);
        file_put_contents($this->filePath, "\xEF\xBB\xBF" . $content);

        $expected = $this->restoreCertificate($this->certificateData());
        self::assertEquals($expected, $this->repository->findById(self::CERTIFICATE_ID));
        self::assertEquals($expected, $this->repository->findByEnrollment(self::ENROLLMENT_ID));
        self::assertEquals([$expected], $this->repository->findByUser(self::USER_ID));
    }

    #[DataProvider('incompleteRecordProvider')]
    public function testLookupsSkipIncompleteRecords(string $missingField): void
    {
        $incomplete = $this->certificateData();
        unset($incomplete[$missingField]);
        $expected = $this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID);
        $this->writeCertificates([null, 'invalid', 42, [], $incomplete, $expected]);

        self::assertNull($this->repository->findById(self::CERTIFICATE_ID));
        self::assertNull($this->repository->findByEnrollment(self::ENROLLMENT_ID));
        self::assertEquals($this->restoreCertificate($expected), $this->repository->findById(self::SECOND_CERTIFICATE_ID));
        self::assertEquals($this->restoreCertificate($expected), $this->repository->findByEnrollment(self::SECOND_ENROLLMENT_ID));
        self::assertEquals([$this->restoreCertificate($expected)], $this->repository->findByUser(self::USER_ID));
    }

    public static function incompleteRecordProvider(): iterable
    {
        foreach (['id', 'enrollmentId', 'userId', 'coursedId', 'issuedAt', 'certificateCode'] as $field) {
            yield $field => [$field];
        }
    }

    public function testSavePersistsAllFieldsAndAllowsReadingFromANewRepository(): void
    {
        $data = $this->certificateData();
        $data['certificateCode'] = 'EDU-' . self::COURSE_ID . '-' . self::USER_ID . '-à-0123';
        $certificate = $this->restoreCertificate($data);

        self::assertTrue($this->repository->save($certificate));
        self::assertSame([$data], $this->readCertificates());

        $repository = new JsonCertificateRepository($this->filePath);
        self::assertEquals($certificate, $repository->findById($certificate->id));
        self::assertEquals($certificate, $repository->findByEnrollment($certificate->enrollmentId));
    }

    public function testSaveAndLookupPreserveAnIdContainingOnlyZeros(): void
    {
        $data = array_replace($this->certificateData(), [
            'id' => '0000-0000-0000-0000',
            'enrollmentId' => '0000-0000-0000-0000',
            'userId' => '0000-0000-0000-0000',
        ]);
        $certificate = $this->restoreCertificate($data);

        self::assertTrue($this->repository->save($certificate));
        self::assertSame([$data], $this->readCertificates());
        self::assertEquals($certificate, $this->repository->findById('0000-0000-0000-0000'));
        self::assertEquals($certificate, $this->repository->findByEnrollment('0000-0000-0000-0000'));
        self::assertEquals([$certificate], $this->repository->findByUser('0000-0000-0000-0000'));
    }

    public function testSaveAddsACertificateWithoutLosingExistingOnes(): void
    {
        $existing = $this->certificateData();
        $new = $this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID);
        $this->writeCertificates([$existing]);

        self::assertTrue($this->repository->save($this->restoreCertificate($new)));
        self::assertSame([$existing, $new], $this->readCertificates());
    }

    public function testSaveCreatesTheFileWhenItDoesNotExist(): void
    {
        unlink($this->filePath);
        $data = $this->certificateData();

        self::assertTrue($this->repository->save($this->restoreCertificate($data)));
        self::assertSame([$data], $this->readCertificates());
    }

    public function testSaveDoesNotOverwriteAnInvalidExistingFile(): void
    {
        $content = '{invalid json';
        file_put_contents($this->filePath, $content);
        $this->expectException(RuntimeException::class);

        try {
            $this->repository->save($this->restoreCertificate($this->certificateData()));
        } finally {
            self::assertSame($content, file_get_contents($this->filePath));
        }
    }

    public function testSaveReturnsFalseWhenTheTargetCannotBeWritten(): void
    {
        // Il percorso ha un file normale come genitore: non può contenere altri file.
        $repository = new JsonCertificateRepository($this->filePath . '/certificates.json');

        self::assertFalse($repository->save($this->restoreCertificate($this->certificateData())));
        self::assertSame([], $this->readCertificates());
    }

    public function testSaveDoesNotOverwriteTheFileIfJsonEncodingFails(): void
    {
        $this->writeCertificates([$this->certificateData()]);
        $content = file_get_contents($this->filePath);
        self::assertIsString($content);
        $invalid = array_replace($this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID), [
            'certificateCode' => "\xB1\x31",
        ]);
        $this->expectException(JsonException::class);

        try {
            $this->repository->save($this->restoreCertificate($invalid));
        } finally {
            self::assertSame($content, file_get_contents($this->filePath));
        }
    }

    public function testSaveUpdatesAnExistingCertificateWithoutDuplicates(): void
    {
        $original = $this->certificateData();
        $other = $this->certificateData(self::SECOND_CERTIFICATE_ID, self::SECOND_ENROLLMENT_ID);
        $updated = array_replace($original, [
            'enrollmentId' => self::UPDATED_ENROLLMENT_ID,
            'userId' => self::OTHER_USER_ID,
            'coursedId' => self::UPDATED_COURSE_ID,
            'issuedAt' => '2026-11-22 15:30:00',
            'certificateCode' => 'EDU-' . self::UPDATED_COURSE_ID . '-' . self::OTHER_USER_ID . '-9999',
        ]);
        $this->writeCertificates([$original, $other]);

        self::assertTrue($this->repository->save($this->restoreCertificate($updated)));
        self::assertTrue($this->repository->save($this->restoreCertificate($updated)));

        $persisted = $this->readCertificates();
        self::assertTrue(array_is_list($persisted), 'Il file deve contenere una lista JSON.');
        self::assertCount(2, $persisted);
        self::assertEqualsCanonicalizing([$updated, $other], $persisted);
        self::assertEquals($this->restoreCertificate($updated), $this->repository->findById(self::CERTIFICATE_ID));
        self::assertNull($this->repository->findByEnrollment(self::ENROLLMENT_ID));
    }

    private function certificateData(string $id = self::CERTIFICATE_ID, string $enrollmentId = self::ENROLLMENT_ID): array
    {
        return [
            'id' => $id,
            'enrollmentId' => $enrollmentId,
            'userId' => self::USER_ID,
            'coursedId' => self::COURSE_ID,
            'issuedAt' => '2026-11-22 10:00:00',
            'certificateCode' => 'EDU-' . self::COURSE_ID . '-' . self::USER_ID . '-0123',
        ];
    }

    private function restoreCertificate(array $data): Certificate
    {
        return Certificate::restore(
            id: $data['id'],
            enrollmentId: $data['enrollmentId'],
            userId: $data['userId'],
            coursedId: $data['coursedId'],
            issuedAt: $data['issuedAt'],
            certificateCode: $data['certificateCode']
        );
    }

    private function writeCertificates(array $certificates): void
    {
        file_put_contents($this->filePath, json_encode($certificates, JSON_THROW_ON_ERROR));
    }

    private function readCertificates(): array
    {
        $content = file_get_contents($this->filePath);
        self::assertIsString($content);
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);

        return $data;
    }
}
