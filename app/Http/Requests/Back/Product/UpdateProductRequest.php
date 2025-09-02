<?php

namespace App\Http\Requests\Back\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $rules = [
            'title'            => 'required|string|max:191',
            'title_en'         => 'nullable|string|max:191',
            'category_id'      => 'required|exists:categories,id',
            'image'            => 'image',
            'slug'             => "nullable|unique:products,slug," . $this->product->id,
            'publish_date'     => 'nullable|date',
            'spec_type'        => 'required_with:specification_group',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,id',
            'type'             => 'required|in:physical,download',
            'rounding_amount'  => 'required|in:default,no,100,1000,10000,100000',
            'rounding_type'    => 'required|in:default,close,up,down',
            'currency_id'      => 'nullable|exists:currencies,id',
            'discount_per_purchase' => 'nullable|array',
            'discount_per_purchase.*' => 'array:quantity,discount_amount,is_percent',
            'discount_per_purchase.*.quantity' => 'required|numeric|min:0',
            'discount_per_purchase.*.discount_amount' => 'required|numeric',
            'discount_per_purchase.*.is_percent' => 'boolean',
            'gift_products' => ['nullable', 'array'],
            'gift_products.*' => ['array'],
            'gift_products.*.count' => ['numeric', 'min:1'],
            'gift_products.*.products.*' => ['array:product_id,quantity'],
            'gift_products.*.products.*.product_id' => ['exists:'.Product::class.',id'],
            'gift_products.*.products.*.quantity' => ['numeric', 'min:1'],
        ];

        if ($this->input('type') == 'physical') {
            $rules = array_merge($rules, [
                'weight'                => 'required|integer',
                'unit'                  => 'required|string',
                'prices'                => 'required_if:type,physical|array',
                'prices.*.price'        => 'required|numeric|min:0',
                'prices.*.stock'        => 'required|integer',
                'prices.*.attributes'   => "required|array",
                'prices.*.attributes.*' => "nullable|exists:attributes,id",
                'prices.*.cart_max'     => 'nullable|integer',
                'prices.*.discount'     => 'nullable|min:0|max:100',
            ]);
        }

        if ($this->input('type') == 'download') {
            $rules = array_merge($rules, [
                'download_files'                => 'required_if:type,download|array',
                'download_files.*.title'        => 'required|string',
                'download_files.*.file'         => 'required_without:download_files.*.price_id|file',
                'download_files.*.price'        => 'required|numeric|min:0',
                'download_files.*.discount'     => 'nullable|min:0|max:100',
                'download_files.*.status'       => 'required|in:active,inactive',
                'download_files.*.price_id'     => [
                    'nullable', Rule::exists('prices', 'id')->where(function ($query) {
                        return $query->where('product_id', $this->product->id);
                    }),
                ],
            ]);
        }

        return $rules;
    }
}
