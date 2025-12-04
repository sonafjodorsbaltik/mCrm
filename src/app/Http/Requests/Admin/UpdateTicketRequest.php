<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TicketStatusEnum;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(TicketStatusEnum::class)],
            'comment' => ['nullable', 'string', 'max:65535'],
        ];
    }
}