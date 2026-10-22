<?php

namespace App\DTOs;

class UserDTO
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string|null $avatar
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $avatar = null
    ) {}

    /**
     * Convert to array
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