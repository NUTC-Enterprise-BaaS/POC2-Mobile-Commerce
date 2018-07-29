<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PublishAdsRequest extends Request
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
                'company_name'      => 'required',
                'phone'             => 'required',
                'contact_member'    => 'required',
                'title'             => 'required',
                'content'           => 'required'
        ];
    }
}
