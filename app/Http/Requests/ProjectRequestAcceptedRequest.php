<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequestAcceptedRequest extends FormRequest
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
            $projectRequest = $this->route('projectRequest');

            if ($projectRequest->project?->status !== ProjectStatus::PENDING) {
                $validator->errors()->add(
                    'project',
                    __('This project is not pending')
                );
            }
        });
    }
}
