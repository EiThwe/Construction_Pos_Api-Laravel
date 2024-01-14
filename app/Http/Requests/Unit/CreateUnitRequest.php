<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitRequest extends FormRequest
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
            'name' => 'required|string|min:1|unique:units,name',
            'unit_type_id' => 'required|integer|exists:unit_types,id',
            'conversions' => 'array',
            'conversions.*.to_unit_id' => '',
            'conversions.*.value' => 'min:0',
            'conversions.*.status' => 'in:more,less',
        ];
    }
}
