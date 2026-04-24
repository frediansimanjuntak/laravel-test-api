<?php

namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrator';
    case Manager       = 'manager';
    case User          = 'user';

    public function label(): string
    {
        return match($this) {
            self::Administrator => 'Administrator',
            self::Manager       => 'Manager',
            self::User          => 'User',
        };
    }

    /** Fields this role is allowed to write when updating a user record. */
    public function editableFields(): array
    {
        return match($this) {
            self::Administrator => ['name', 'email', 'password', 'role', 'is_active'],
            self::Manager       => ['name', 'email', 'password'],
            self::User          => ['name', 'email', 'password'],
        };
    }
}
