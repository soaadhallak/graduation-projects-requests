<?php

namespace App\Http\Requests;

use App\Rules\HasNoPendingJoinRequest;
use Illuminate\Foundation\Http\FormRequest;

class JoinRequestStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->student?->team_id === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'teamId' => ['required','exists:teams,id',new HasNoPendingJoinRequest()],
            'projectRequestId' => ['required','exists:project_requests,id']
        ];
    }
}
