<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $avatar = null
    ) {}

    /**
     * Convert to array
     *
     * @return array{name: string, email: string, password: string, avatar: string|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'avatar' => $this->avatar,
        ];
    }

    /**
     * Create from request
     *
     * @param  array<string, mixed>  $request
     */
    public static function fromRequest(array $request): self
    {
        return new self(
            $request['name'],
            $request['email'],
            $request['password'],
            $request['avatar'] ?? null
        );
    }
}
