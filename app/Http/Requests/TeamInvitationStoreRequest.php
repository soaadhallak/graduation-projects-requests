<?php

namespace App\Http\Requests;

use App\Rules\InviteMemberRule;
use App\Rules\TeamMemberLimitRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamInvitationStoreRequest extends FormRequest
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
        $team=$this->route('team');

        return [
            'emails'=>['required','array','min:1','max:7',new TeamMemberLimitRule($team)],
            'emails.*'=>['email','max:255','exists:users,email',new InviteMemberRule($team)]
        ];
    }
}
