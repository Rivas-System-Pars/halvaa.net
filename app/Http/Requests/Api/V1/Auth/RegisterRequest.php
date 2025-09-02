<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Rules\NotSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class   RegisterRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:191', 'alpha_num', 'unique:users'],

            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'لطفا یک  نام کاربری معتبر وارد کنید',
            'username.string' => 'لطفا یک  نام کاربری معتبر وارد کنید',
            'username.regex' => 'لطفا یک نام کاربری  معتبر وارد کنید',
            'username.digits' => 'لطفا یک  نام کاربری معتبر وارد کنید',
            'username.unique' => ' نام کاربری وارد شده تکراری است',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'phone_number' => convertPersianToEnglish($this->input('phone_number'))
        ]);
    }
}
