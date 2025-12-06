<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

/**
 * Data Transfer Object for creating a new ticket.
 * 
 * Contains all data needed to create a ticket including
 * customer information and optional file attachments.
 */
readonly class CreateTicketDto 
{
    /**
     * @param string $customerName Customer's full name
     * @param string $customerPhone Customer's phone in E.164 format
     * @param string $customerEmail Customer's email address
     * @param string $subject Ticket subject line
     * @param string $content Ticket message content
     * @param UploadedFile[]|null $files Optional file attachments
     */
    public function __construct(
        public string $customerName,
        public string $customerPhone,
        public string $customerEmail,
        public string $subject,
        public string $content,
        public ?array $files = null,
    ){}

    /**
     * Create DTO from validated request data array.
     *
     * @param array $data Validated form data with keys: name, phone, email, subject, content, files
     * @return self New DTO instance
     */
    public static function fromArray(array $data): self
    {
        return new self(
            customerName: $data['name'],
            customerPhone: $data['phone'],
            customerEmail: $data['email'],
            subject: $data['subject'],
            content: $data['content'],
            files: $data['files'] ?? null,
        );
    }
}