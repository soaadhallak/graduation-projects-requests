<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveMemberRequest extends FormRequest
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
            //
        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $team = $this->route('team');
            $student = $this->route('student');

            if ($student->team_id !== $team->id) {
                $validator->errors()->add(
                    'member',
                    __('This student does not belong to this team')
                );
            }
        });
    }
}
