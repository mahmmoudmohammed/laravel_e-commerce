<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $request = request();
        $method = $request->method();
        $rules = [];
        switch ($method) {
            case 'POST':
                $rules += [
                    'name' => 'required|max:255|string',
                    'password' => ['required', 'string', 'min:6'],
                    'contact' => 'required|min:11|max:14|string',
                    'email' => 'required|string|email|unique:users'
                ];
                break;
            case 'PUT':
                $rules += [
                    'name' => 'required|max:255|string',
                    'password' => ['required', 'string', 'min:6'],
                    'contact' => 'required|min:11|max:14|string',
                    'email' => 'required|string|email'
                ];
                break;
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'email.unique' => 'email already exists try another one '

        ];
    }
}
