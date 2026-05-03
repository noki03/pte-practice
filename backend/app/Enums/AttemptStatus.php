<?php

namespace App\Enums;

enum AttemptStatus: string
{
    case InProgress = 'in_progress';
    case Submitted  = 'submitted';
    case Scored     = 'scored';
    case Failed     = 'failed';

    public function isTerminal(): bool
    {
        return in_array($this, [self::Scored, self::Failed]);
    }

    public function canTransitionTo(self $next): bool
    {
        return match($this) {
            self::InProgress => $next === self::Submitted || $next === self::Failed,
            self::Submitted  => $next === self::Scored || $next === self::Failed,
            self::Scored     => false,
            self::Failed     => false,
        };
    }
}
