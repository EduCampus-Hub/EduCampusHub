<?php

namespace Tests\Unit\Domain;

use app\Domain\Enum\UserRoleEnum;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;

class DomainTests extends TestCase
{
    #[Test]
    public function enum_exposes_all_supported_roles(): void
    {
        $this->assertSame('ADMIN', UserRoleEnum::ADMIN->value);
        $this->assertSame('DOCENTE', UserRoleEnum::DOCENTE->value);
        $this->assertSame('STUDENTE', UserRoleEnum::STUDENTE->value);
        $this->assertSame('ATA', UserRoleEnum::ATA->value);

        $this->assertSame(UserRoleEnum::ADMIN, UserRoleEnum::from('ADMIN'));
    }

    #[Test]
    public function enum_rejects_unknown_role(): void
    {
        $this->expectException(\ValueError::class);
        UserRoleEnum::from('USER');
    }

    #[Test]
    public function user_contains_all_domain_data(): void
    {
        $user = new User(
            'user-123',
            'Mario Rossi',
            'mario@example.com',
            'stored-password-hash',
            UserRoleEnum::STUDENTE,
            '3A'
        );

        $this->assertSame('user-123', $user->id);
        $this->assertSame('Mario Rossi', $user->name);
        $this->assertSame('mario@example.com', $user->email);
        $this->assertSame('stored-password-hash', $user->password_hash);
        $this->assertSame(UserRoleEnum::STUDENTE, $user->role);
        $this->assertSame('3A', $user->user_class);
    }

    #[Test]
    public function user_can_have_no_class(): void
    {
        $user = new User(
            'user-456',
            'Anna Bianchi',
            'anna@example.com',
            'another-hash',
            UserRoleEnum::DOCENTE,
            null
        );

        $this->assertNull($user->user_class);
    }

    #[Test]
    public function user_returns_its_password_hash(): void
    {
        $user = new User(
            'user-789',
            'Luigi Verdi',
            'luigi@example.com',
            'password-hash',
            UserRoleEnum::ATA,
            null
        );

        $this->assertSame('password-hash', $user->getPasswordHash());
    }

    #[Test]
    public function repository_interface_declares_user_operations(): void
    {
        $reflection = new ReflectionClass(UserRepositoryInterface::class);

        $this->assertTrue($reflection->hasMethod('findById'));
        $this->assertTrue($reflection->hasMethod('findByEmail'));
        $this->assertTrue($reflection->hasMethod('findAll'));
        $this->assertTrue($reflection->hasMethod('save'));

        $findById = $reflection->getMethod('findById');
        $this->assertSame('?app\Domain\Model\User', (string) $findById->getReturnType());

        $findByEmail = $reflection->getMethod('findByEmail');
        $this->assertSame('?app\Domain\Model\User', (string) $findByEmail->getReturnType());

        $save = $reflection->getMethod('save');
        $this->assertSame('bool', (string) $save->getReturnType());
    }
}
