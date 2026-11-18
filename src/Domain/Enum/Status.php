<?php
namespace App\Domain\Enum;
enum Status
{
    case Pending;
    case Confirmed;
    case Negated;

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($status->name === $name) {
                return $status;
            }
        }

        throw new \ValueError("$name is not a valid case name for enum " . self::class);
    }
}