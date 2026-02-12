<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Project::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('projects','title')],
            'description' => ['required', 'string', 'min:20' , 'max:255'],
            'tools' => ['required', 'string' , 'max:255'],
            'supervisorId' => ['required', 'exists:users,id'],
            'grade' => ['required', 'numeric'],
            'status' => ['required'],
            'files' => ['nullable', 'array', 'min:1', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,zip,docx,png,jpg', 'max:10240'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => ProjectStatus::ARCHIVED->value
        ]);
    }
}
