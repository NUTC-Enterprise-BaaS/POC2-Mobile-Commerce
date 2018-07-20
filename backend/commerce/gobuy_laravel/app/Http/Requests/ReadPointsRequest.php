<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReadPointsRequest extends Request
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
             'timestamp_start'      => 'required',
             'timestamp_end'        => 'required'
        ];
    }
}
