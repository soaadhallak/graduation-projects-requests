<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['nullable','string','max:255'],
            'email' => ['nullable','email', Rule::unique('users','email')->ignore($this->user()->id ?? null)],
            'universityNumber' => ['nullable','string','max:10', Rule::unique('students','university_number')->ignore($this->user()->id ?? null, 'user_id')],
            'skills' => ['nullable','string','max:255'],
            'majorId' => ['nullable','exists:majors,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
