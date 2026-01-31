<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptJoinRequestTeamRequest extends FormRequest
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
            $teamJoinRequest = $this->route('team_join_request');

            if (!$teamJoinRequest->projectRequest->is_looking_for_members) {
                $validator->errors()->add(
                    'project',
                    __('This project is complete')
                );
            }
        });
    }
}
