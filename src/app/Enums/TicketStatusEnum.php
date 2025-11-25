<?php

namespace App\Enums;

enum TicketStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';

    public static function values(): array 
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string {
        return match($this) {
            self::NEW => 'New',
            self::IN_PROGRESS => 'In Progress',
            self::CLOSED => 'Closed',
        };
    }    
}