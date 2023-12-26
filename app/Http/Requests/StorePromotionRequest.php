<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,amount',
            'amount'=>'required|numeric',
            'started_at' => 'required|date',
            'expired_at' => 'required|date|after:started_at',
            'product_id' => 'required',
        ];
    }
}
