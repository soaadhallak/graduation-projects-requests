<?php

namespace App\Http\Requests;

use App\Rules\ActiveSupervisorInvitationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupervisorInvitationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required','email',new ActiveSupervisorInvitationRule,Rule::unique("users","email")],
            'token' => ['required'],
            'expiresAt' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'token' => Str::random(64),
            'expiresAt' => now()->addDays(2)
        ]);
    }
}
