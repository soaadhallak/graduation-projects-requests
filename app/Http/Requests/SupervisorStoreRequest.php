<?php

namespace App\Http\Requests;

use App\Rules\CheckSupervisorInvitationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupervisorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::exists('supervisor_invitation','email')],
            'token' => ['required', 'string', Rule::exists('supervisor_invitation','token'), new CheckSupervisorInvitationRule],
            'name'  => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
