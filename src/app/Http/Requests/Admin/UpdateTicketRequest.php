<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TicketStatusEnum;
use Illuminate\Validation\Rules\Enum;

/**
 * Form Request for updating ticket data in admin panel.
 * 
 * Validates status changes and optional comments.
 * Only accessible by admin or manager roles.
 */
class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Get the validation rules for ticket update.
     *
     * @return array<string, mixed> Validation rules
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(TicketStatusEnum::class)],
            'comment' => ['nullable', 'string', 'max:65535'],
        ];
    }
}