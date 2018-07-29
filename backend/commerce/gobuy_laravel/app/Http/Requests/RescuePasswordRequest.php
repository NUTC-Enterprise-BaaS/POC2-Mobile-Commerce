<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RescuePasswordRequest extends Request
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
                'email'                 => 'required|email|max:255',
                'password'              => 'required|min:6|confirmed',
                'verify_code'           => 'required',
        ];
    }
}
