<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditCategoryRequest extends Request
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
    public function rules() {
        return [
            'category' => 'required|max:25|min:2',
            'category_ru' => 'required|max:25|min:2',
            'h1' => 'max:255|min:2',
            'h1_ru' => 'max:255|min:2',
            'slug' => 'required|max:255|min:2|regex:/^[a-z-\d]+$/',
        ];
    }

    public function messages()
    {
        return [
            'regex' => 'url може містити тільки англійськи символи та дефіс',
        ];
    }
}
