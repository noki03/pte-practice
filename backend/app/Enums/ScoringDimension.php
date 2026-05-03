<?php

namespace App\Enums;

enum ScoringDimension: string
{
    case Content      = 'content';
    case Fluency      = 'fluency';
    case Pronunciation = 'pronunciation';
    case Grammar      = 'grammar';
    case Vocabulary   = 'vocabulary';
    case Spelling     = 'spelling';
    case Form         = 'form';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
