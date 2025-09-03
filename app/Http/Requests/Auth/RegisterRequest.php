<?php

namespace App\Http\Requests\Auth;

use App\Rules\NotSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone_number' => ['required', 'regex:/^09\d{9}$/',],
            'national_code' => ['nullable', 'regex:/^\d{10}$/'],
            'birth' => ['required', 'date', 'before:today'],
            'death' => ['required', 'date', 'after:birth'],
            'birth_city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'death_city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/', // حداقل یک حرف و یک عدد
            ],
            'captcha' => ['required', 'captcha'],
            'is_private' => ['nullable', 'in:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            // 'level' => ['nullable'],
            'bio' => ['nullable', 'string'],




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

            'phone_number.required' => 'لطفا شماره موبایل معتبر وارد کنید',
            'phone_number.regex' => 'فرمت شماره موبایل صحیح نیست (مثال: 09123456789)',
            // 'phone_number.unique' => 'این شماره موبایل قبلا ثبت شده است',

            'national_code.required' => 'کد ملی الزامی است',
            'national_code.regex' => 'کد ملی باید 10 رقم باشد',

            'birth.required' => 'تاریخ تولد الزامی است',
            'birth.date' => 'تاریخ تولد باید یک تاریخ معتبر باشد',
            'birth.before' => 'تاریخ تولد باید قبل از امروز باشد',

            'death.required' => 'تاریخ فوت الزامی است',
            'death.date' => 'تاریخ فوت باید یک تاریخ معتبر باشد',
            'death.after' => 'تاریخ فوت باید بعد از تاریخ تولد باشد',

            'password.regex' => 'رمز عبور باید شامل حداقل یک حرف و یک عدد باشد.',


        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => convertPersianToEnglish($this->input('username'))
        ]);
    }
}
