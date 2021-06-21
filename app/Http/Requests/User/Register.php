<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class Register extends BaseRequest
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
        $professions =  implode(',', config('constants.PROFESSIONS'));
        $role = implode(',', config('constants.PROVIDER_ROLES'));
        if ($this->request->all()['profession'] == config('constants.PROFESSIONAL')) {
            $role = implode(',', config('constants.PROFESSIONAL_ROLES'));
        }
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'profession' => 'required|in:' . $professions,
            'role' => 'required|in:' . $role
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.email' => 'Please enter a valid email',
            'email.required' => 'Email is required!',
            'email.unique' => 'This email has already been used',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must contain minimum 8 characters'
        ];
    }
}
