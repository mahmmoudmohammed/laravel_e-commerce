<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $request = request();
        $method = $request->method();
        $rules = [];
        switch ($method) {
            case 'POST':
            case 'PUT':
                $rules += [
                    'name'  => 'required|string',
                    'description'  => 'required|string',
                    'price'  => 'required|numeric',
                    'in_stock'  => 'required|integer',
                    'price_after'  => 'required|numeric',
                ];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [

        ];
    }
}
