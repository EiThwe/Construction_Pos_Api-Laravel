<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|min:1',
            'actual_price' => 'required|numeric',
            'primary_unit_id' => 'required|exists:units,id',
            'primary_price' => 'required|numeric',
            'remark' => 'nullable|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'units' => 'required|array',
            'units.*.unit_id' => 'required|min:1',
            'units.*.price' => 'required|numeric|min:0',
        ];
    }
}
