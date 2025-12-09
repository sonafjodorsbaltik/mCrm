<?php

namespace App\Enums;

/**
 * Enum representing possible ticket statuses.
 * 
 * Used for type-safe status handling throughout the application.
 */
enum TicketStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';

    /**
     * Get all possible status values as strings.
     *
     * @return string[] Array of status string values
     */
    public static function values(): array 
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get localized human-readable label for the status.
     * 
     * Uses Laravel __() helper for i18n support.
     * Translations defined in: lang/{locale}/enums.php
     *
     * @return string Localized display label (e.g., 'In Progress' or 'В работе')
     */
    public function label(): string {
        return __("enums.ticket_status.{$this->value}");
    }
    
    /**
     * Check if this status requires setting replied_at timestamp.
     * 
     * Returns true for IN_PROGRESS and CLOSED statuses.
     *
     * @return bool True if replied_at should be set
     */
    public function requiresReplyDate(): bool
    {
        return match($this) {
            self::IN_PROGRESS, self::CLOSED => true,
            default => false,
        };
    }
}