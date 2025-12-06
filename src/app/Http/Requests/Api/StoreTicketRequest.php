<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\E164PhoneNumber;

/**
 * Form Request for creating a new ticket via API.
 * 
 * Validates all incoming data for ticket creation including
 * customer info, ticket content, and file attachments.
 */
class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Always returns true as this is a public API endpoint.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules for ticket creation.
     *
     * @return array<string, mixed> Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', new E164PhoneNumber()],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:65535'],
            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'mimes:jpg,png,pdf,doc,docx', 'max:10240'],
        ];
    }
}