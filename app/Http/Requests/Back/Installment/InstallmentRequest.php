<?php

namespace App\Http\Requests\Back\Installment;

use Illuminate\Foundation\Http\FormRequest;

class InstallmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'prepayment_percentage' => ['required', 'numeric', 'between:0,100'],
            'fee_percentage' => ['required', 'numeric', 'between:0,100'],
			'period' => ['nullable', 'numeric', 'min:1'],
            'installments_count' => ['required', 'numeric', 'min:1'],
			'is_active' => ['nullable','boolean'],
			'products' => ['nullable','array'],
			'products.*' => ['exists:products,id'],
        ];
    }
}
