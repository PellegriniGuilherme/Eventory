<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockGeneralAdjustmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'exists:stock_generals,id'],
            'product_id' => ['required', 'exists:products,id'],
            'adjustment_reason' => ['required', 'string'],
            'amount' => ['required', 'integer']
        ];
    }
}