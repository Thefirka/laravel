<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterApiRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
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
