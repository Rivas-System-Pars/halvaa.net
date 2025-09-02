<?php

namespace App\Http\Requests\Auth;

use App\Rules\NotSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
        return [
            'first_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'last_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'phone_number' => ['required', 'regex:/^09\d{9}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed:confirmed'],
            'captcha' => ['required', 'captcha'],
            'level' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [

            'phone_number.required' => 'لطفا شماره موبایل معتبر وارد کنید',
            'phone_number.regex' => 'فرمت شماره موبایل صحیح نیست (مثال: 09123456789)',
            'phone_number.unique' => 'این شماره موبایل قبلا ثبت شده است',

        ];
    }


}
