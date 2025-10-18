<?php

namespace App\Enums;

enum WordStatus: string
{
    case NEW = 'NEW';
    case REPEATED = 'REPEATED';
    case REPEATED_FAILED = 'REPEATED_FAILED';
    

    // Optional: Helper methods
    public function label(): string
    {
        return match($this) {
            self::NEW => 'NEW',
            self::REPEATED => 'REPEATED',
            self::REPEATED_FAILED => 'REPEATED_FAILED',
            
        };
    }
}
