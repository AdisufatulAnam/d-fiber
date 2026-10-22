<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case VIEWER = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::EDITOR => 'Editor',
            self::VIEWER => 'Viewer',
        };
    }
}