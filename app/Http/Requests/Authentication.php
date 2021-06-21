<?php

namespace App\Http\Requests;

class Authentication extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/',
            'password' => 'required',
            'remember_me' => 'boolean',
        ];
    }
}
