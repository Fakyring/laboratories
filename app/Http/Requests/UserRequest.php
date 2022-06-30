<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

    public function messages()
    {
        return [
            'email.required'=>'Поле email является обязательным',
            'email.min' => 'Email должен быть больше :min символов',
            'email.max'=>'Email должен быть меньше :max символов',
            'password.required'=>'Поле пароль является обязательным',
            'password.min' => 'Пароль должен быть больше :min символов',
            'password.max'=>'Пароль должен быть меньше :max символов'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>['required', 'email', 'min:10', 'max:100'],
            'password'=>['required', 'min:8', 'max:20']
        ];
    }
}
