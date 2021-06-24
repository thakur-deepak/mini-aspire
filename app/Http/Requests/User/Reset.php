<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;

class Reset extends BaseRequest
{
    public function __construct(Request $request)
    {
        $request['email'] = $request['email'] ??  null;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }
}
