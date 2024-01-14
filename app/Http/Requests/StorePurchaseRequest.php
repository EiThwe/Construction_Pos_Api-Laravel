<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'place' => 'required|string',
            'cost' => 'required|numeric',
            'remark' => 'nullable|string',
            'purchase_items' => 'required|array|min:1',
            'purchase_items.*.name' => 'required|string',
            'purchase_items.*.quantity' => 'required|integer|min:1',
            'purchase_items.*.unit_id' => 'required',
        ];
    }
}
