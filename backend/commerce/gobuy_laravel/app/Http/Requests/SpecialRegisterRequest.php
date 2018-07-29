<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SpecialRegisterRequest extends Request
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
            'store_name'            => 'required',
            'store_type'            => 'required',
            'category_employment'   => 'required',
            'contact_person'        => 'required',
            'contact_person_sex'    => 'required',
        ];
    }
}
