<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string|min:1',
            'actual_price' => 'numeric',
            'primary_unit_id' => '',
            'primary_price' => 'numeric',
            'remark' => 'nullable|string|max:255',
            'categories' => 'array',
            'categories.*' => '',
            'units.*.unit_id' => '',
            'units.*.price' => 'numeric|min:0',
        ];
    }
}
