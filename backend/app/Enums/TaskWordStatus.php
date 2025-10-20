<?php

namespace App\Enums;

enum TaskWordStatus: string
{
    case NEW = 'NEW';
    case DONE = 'DONE';
  
    // Optional: Helper methods
    public function label(): string
    {
        return match($this) {
            self::NEW => 'new',
            self::DONE=> 'done',
             
        };
    }
}
