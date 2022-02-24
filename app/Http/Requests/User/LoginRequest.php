<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractFormRequest;

class LoginRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }
}
