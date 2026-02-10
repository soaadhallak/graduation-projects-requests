<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectFilterRequest extends FormRequest
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
            'filter' => ['sometimes', 'array'],
            'filter.status' => ['sometimes', Rule::enum(ProjectStatus::class)],
            'filter.search' => ['sometimes', 'string', 'max:100'],
            'perPage' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'filter.fromTo' => ['sometimes', 'array', 'size:2'],
            'filter.fromTo.*' => ['integer', 'min:0', 'max:100'],
            'filter.supervisorId' => ['sometimes', 'string', Rule::exists('users','id')]
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('filter.fromTo') && is_string($this->filter['fromTo'])) {
            $this->merge([
                'filter' => array_merge($this->filter, [
                    'fromTo' => explode(',', $this->filter['fromTo'])
                ])
            ]);
        }
    }
}
