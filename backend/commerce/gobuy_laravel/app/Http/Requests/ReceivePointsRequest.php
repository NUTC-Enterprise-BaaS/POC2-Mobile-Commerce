<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReceivePointsRequest extends Request
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
            'state'                 => 'required',
            'check_id'              => 'required',
            'receive_email'         => 'required',
            'send_email'            => 'required',
        ];
    }
}
