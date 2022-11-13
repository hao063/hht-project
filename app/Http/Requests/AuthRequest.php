<?php

namespace App\Http\Requests;


class AuthRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }

//    public function messages()
//    {
//        return [
//            'email.required'    => '',
//            'email.email'       => '',
//            'password.required' => '',
//        ];
//    }
}
