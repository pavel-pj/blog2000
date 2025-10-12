<?php

namespace App\Enums;

enum WordStatus: string
{
    case NEW = 'NEW';
    case REPEATED = 'REPEATED';
    

    // Optional: Helper methods
    public function label(): string
    {
        return match($this) {
            self::NEW => 'NEW',
            self::REPEATED => 'REPEATED',
            
        };
    }
}
