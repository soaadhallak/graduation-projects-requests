<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class CompleteProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $projectRequest = $this->route('projectRequest');
        return $this->user()->can('update', $projectRequest->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => ProjectStatus::ARCHIVED->value
        ]);
    }
}
