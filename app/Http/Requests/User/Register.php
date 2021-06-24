<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;

class Register extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:120|regex:' . config('constants.NAME_REGEX'),
            'last_name'  => 'required|max:120|regex:' . config('constants.NAME_REGEX'),
            'email'      => 'required|email|unique:users|regex:' . config('constants.EMAIL_REGEX'),
            'password'   => 'required|min:8|regex:' . config('constants.PASSWORD_REGEX')
        ];
    }

    public function messages(): array
    {
        return trans('messages.validation');
    }
}
