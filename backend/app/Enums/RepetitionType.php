<?php

namespace App\Enums;

enum RepetitionType: string
{
    case NEW = 'NEW';
    case IMPORTANT = 'IMPORTANT';
    case REPEATED = 'REPEATED';
 
    // Optional: Helper methods
    public function label(): string
    {
        return match($this) {
            self::NEW => 'new',
            self::IMPORTANT => 'important',
            self::REPEATED => 'repeated',
             
        };
    }
}
