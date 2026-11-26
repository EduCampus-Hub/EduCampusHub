<?php

namespace Tests\Unit\Application;

use app\Application\DTO\LoginDTO;
use app\Application\DTO\RegisterUserDTO;
use app\Application\Service\SessionManagerInterface;
use app\Application\UseCase\AuthenticateUserUseCase;
use app\Application\UseCase\RegisterUserUseCase;
use app\Domain\Enum\UserRoleEnum;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ApplicationTests extends TestCase
{
    #[Test]
    public function login_dto_contains_credentials(): void
    {
        $dto = new LoginDTO('user@example.com', 'secret');

        $this->assertSame('user@example.com', $dto->email);
        $this->assertSame('secret', $dto->password_hash);
    }

    #[Test]
    public function register_dto_contains_complete_user_data(): void
    {
        $dto = new RegisterUserDTO(
            '1',
            'Mario Rossi',
            'mario@example.com',
            'secret',
            UserRoleEnum::STUDENTE,
            '3A'
        );

        $this->assertSame('Mario Rossi', $dto->name);
        $this->assertSame('mario@example.com', $dto->email);
        $this->assertSame(UserRoleEnum::STUDENTE, $dto->role);
        $this->assertSame('3A', $dto->user_class);
    }

    #[Test]
    public function register_rejects_existing_email(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('findByEmail')->willReturn(
            new User('1', 'Existing', 'same@example.com', 'hash', UserRoleEnum::STUDENTE, null)
        );

        $useCase = new RegisterUserUseCase($repository);
        $dto = new RegisterUserDTO('2', 'Mario', 'same@example.com', 'secret', UserRoleEnum::STUDENTE, null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Esiste già un utente con questa email.');
        $useCase->execute($dto);
    }

    #[Test]
    public function register_hashes_password_and_saves_user(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->expects($this->once())->method('findByEmail')->with('mario@example.com')->willReturn(null);
        $repository->expects($this->once())->method('save')->with(
            $this->callback(fn(User $user) => password_verify('secret', $user->password_hash))
        )->willReturn(true);

        $useCase = new RegisterUserUseCase($repository);
        $dto = new RegisterUserDTO('2', 'Mario Rossi', 'mario@example.com', 'secret', UserRoleEnum::DOCENTE, null);
        $user = $useCase->execute($dto);

        $this->assertSame('Mario Rossi', $user->name);
        $this->assertTrue(password_verify('secret', $user->password_hash));
    }

    #[Test]
    public function authenticate_accepts_valid_credentials(): void
    {
        $hash = password_hash('secret', PASSWORD_BCRYPT);
        $user = new User('1', 'Mario', 'mario@example.com', $hash, UserRoleEnum::STUDENTE, '3A');
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('findByEmail')->with('mario@example.com')->willReturn($user);

        $session = $this->createMock(SessionManagerInterface::class);
        $session->expects($this->once())->method('setAuthenticatedUser')->with($user);

        $useCase = new AuthenticateUserUseCase($repository, $session);

        $dto = new LoginDTO('mario@example.com', 'secret');
        $this->assertSame($user, $useCase->execute($dto));
    }

    #[Test]
    public function authenticate_rejects_unknown_and_invalid_password(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('findByEmail')->willReturn(null);

        $session = $this->createMock(SessionManagerInterface::class);
        $session->expects($this->never())->method('setAuthenticatedUser');

        $useCase = new AuthenticateUserUseCase($repository, $session);

        $this->assertNull($useCase->execute(new LoginDTO('missing@example.com', 'secret')));
    }

    #[Test]
    public function session_manager_contract_declares_required_methods(): void
    {
        $reflection = new \ReflectionClass(SessionManagerInterface::class);

        $this->assertTrue($reflection->hasMethod('setAuthenticatedUser'));
        $this->assertTrue($reflection->hasMethod('getAuthenticatedUser'));
        $this->assertTrue($reflection->hasMethod('isAuthenticated'));
        $this->assertTrue($reflection->hasMethod('logout'));
    }
}
