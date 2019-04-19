<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request {

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
    public function rules() {
        return [
            'code' => '|unique:products|numeric',
            'product_name_ru' => 'required|max:75|min:3',
            'product_name' => 'required|max:75|min:3',
            'product_qty' => 'max:2|min:1',
            'product_sku' => 'unique:products',
            'price' => 'required|max:9||min:1',
            'reduced_price' => 'max:9||min:1',
            'category' => 'required',
//            'cat_id.*' => 'required',
            'brand_id' => 'required',
            'description' => 'required|max:2500|min:10',
            'product_spec' => 'max:3500',
            'color' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'color.required' => "Поле колір  є обов'язкове",
            'cat_id.required' => "Поле є обов'язкове"

        ];
    }

}

