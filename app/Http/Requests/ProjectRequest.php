<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum as EnumRule;
use App\Enums\ProjectStatus;

abstract class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function commonRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'unit_price' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => ['required', new EnumRule(ProjectStatus::class)],
            'memo' => 'nullable|string',
        ];
    }
}
