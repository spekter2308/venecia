<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegistrationRequest extends Request {

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
            'username' => 'required|max:20|min:3|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6|max:16',
        ];
    }

    public function messages()
    {
        return [
            'unique' => trans("messages.validation_unique"),
            'confirmed' => trans("messages.validation_confirmed"),
        ];
    }

}