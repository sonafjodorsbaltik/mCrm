<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TicketStatusEnum;

/**
 * Form Request for updating ticket status via AJAX.
 * 
 * Validates status value and provides helper method
 * to get the validated status as an Enum instance.
 */
class UpdateTicketStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users with admin/manager role can update status
        // (already handled by route middleware, but double-check here)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:' . implode(',', TicketStatusEnum::values())
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status value. Allowed values: ' . implode(', ', TicketStatusEnum::values()),
        ];
    }

    /**
     * Get the validated status as Enum instance.
     */
    public function getStatus(): TicketStatusEnum
    {
        return TicketStatusEnum::from($this->validated()['status']);
    }
}
