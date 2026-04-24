<?php

namespace App\DTOs;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $role = 'user',
        public ?bool $is_active = true,
    ) {}
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            role:      $data['role']     ?? 'user',
            is_active: $data['is_active'] ?? true,
        );
    }
}