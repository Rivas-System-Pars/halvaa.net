<?php

namespace Themes\DefaultTheme\src\Requests;

use App\Models\Gateway;
use App\Models\Installment;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $gateways = Gateway::active()->pluck('key')->toArray();
        $cart     = get_cart();
        $rules = [
            'name' => 'required|string',
            'mobile' => 'required|string|regex:/(09)[0-9]{9}/|digits:11',
            'gateway' => 'required|in:wallet,' . implode(',', $gateways),
            'description' => 'nullable|string|max:1000',
            'settlement_type' => ['required', Rule::in(array_merge(Order::SETTLEMENT_TYPES,Installment::query()->pluck('id')->toArray()))],
        ];

        if ($cart && $cart->hasPhysicalProduct()) {
            $rules = array_merge($rules, [
                'province_id' => 'required|exists:provinces,id',
                'city_id'     => 'required|exists:cities,id',
                'postal_code' => 'required|numeric|digits:10',
                'address'     => 'required|string|max:300',
            ]);
        }

        return $rules;
    }
}
