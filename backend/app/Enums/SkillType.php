<?php

namespace App\Enums;

enum SkillType: string
{
    case Speaking  = 'speaking';
    case Writing   = 'writing';
    case Reading   = 'reading';
    case Listening = 'listening';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function colorHex(): string
    {
        return match($this) {
            self::Speaking  => '#6366f1',
            self::Writing   => '#10b981',
            self::Reading   => '#f59e0b',
            self::Listening => '#3b82f6',
        };
    }

    public function displayOrder(): int
    {
        return match($this) {
            self::Speaking  => 1,
            self::Writing   => 2,
            self::Reading   => 3,
            self::Listening => 4,
        };
    }
}
