<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

readonly class CreateTicketDto 
{
    public function __construct(
        public string $customerName,
        public string $customerPhone,
        public string $customerEmail,
        public string $subject,
        public string $content,
        public ?array $files = null,
    ){}

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