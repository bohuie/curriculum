<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:invitations',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Invitation with this email address already requested.',
        ];
    }
}
