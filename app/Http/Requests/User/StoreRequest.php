<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractFormRequest;

class StoreRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
//            'email' => 'required',
            'password' => 'required|min:3',
        ];
    }
}
