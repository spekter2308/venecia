<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdminUpdateReviewRequest extends Request
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
            'product_id' => 'exists:products,id',
            'name' => 'required|max:255|min:2',
            'message' => 'required|max:1000|min:2',
        ];
    }

    public function messages()
    {
        return [
            'max' => trans("messages.validation_max"),
            'min' => trans("messages.validation_min"),
            'required' => trans("messages.validation_required"),
        ];
    }
}