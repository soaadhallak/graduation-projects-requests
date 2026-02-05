<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Models\ProjectRequest;
use App\Rules\CheckMaxMembersRule;
use App\Rules\CheckTeamSizeRule;
use App\Rules\HasNoAcceptedProjectRule;
use App\Rules\InTeamRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequestStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create',ProjectRequest::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>['required','string','max:255',Rule::unique('projects','title'),new InTeamRule(),new HasNoAcceptedProjectRule()],
            'description'=>['required','string','max:255','min:25'],
            'tools'=>['required','string','max:255'],
            'files'=>['nullable','array','min:1','max:5'],
            'files.*'=>['file','mimes:pdf,zip,docx,png,jpg','max:10240'],
            'supervisorId'=>['required','exists:users,id'],
            'isLookingForMembers'=>['nullable','boolean',new CheckTeamSizeRule()],
            'maxNumber'=>['required_if:isLookingForMembers,true','integer','min:1',new CheckMaxMembersRule()],
            'status'=>['sometimes']
        ];
    }

    protected function prepareForValidation()
    {
        $isLooking = $this->boolean('isLookingForMembers',false);

        $this->merge([
            'status' => $isLooking ? ProjectStatus::OPEN->value : ProjectStatus::PENDING->value,
        ]);
    }
}
