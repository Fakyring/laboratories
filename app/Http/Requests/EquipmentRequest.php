<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
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
            'eqName.required'=>'Поле наименования является обязательным',
            'eqName.min' => 'Наименование должно быть больше :min символов',
            'eqName.max'=>'Наименование должно быть меньше :max символов',
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
            'eqName'=>['required', 'min:2', 'max:100']
        ];
    }
}
