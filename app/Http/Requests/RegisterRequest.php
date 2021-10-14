<?php

namespace App\Http\Requests;

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
            'name' => 'required|alpha|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:8|max:12',
        ];

    }
    public function messages()
    {
        return [
            'email.unique' => 'такой имейл уже есть',
        ];
    }
}
