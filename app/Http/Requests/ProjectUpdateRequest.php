<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $this->user()->can('update', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255', Rule::unique('projects','title')],
            'description' => ['sometimes', 'string', 'max:255', 'min:25'],
            'tools' => ['sometimes', 'string', 'max:255'],
            'files' => ['nullable', 'array', 'min:1', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,zip,docx,png,jpg', 'max:10240'],
            'supervisorId' => ['sometimes','exists:users,id'],
            'grade' => ['sometimes', 'numeric']
        ];
    }
}
