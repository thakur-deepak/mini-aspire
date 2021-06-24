<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LoanApprove extends FormRequest
{
    public function __construct(Request $request)
    {
        $request->request->add(['user_id' => $request->user()->id]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'is_approved' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return trans('messages.validation');
    }
}
