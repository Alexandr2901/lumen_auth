<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AbstractFormRequest;

class RefreshRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'refresh_token' => 'string|required|min:60|exists:users,refresh_token',
        ];
    }
}
