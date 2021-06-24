<?php

namespace App\Http\Requests;

use App\Rules\Double;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Loan extends FormRequest
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
            'amount' => ['required', new Double()],
            'loan_term' => 'required'
        ];
    }

    public function messages(): array
    {
        return trans('messages.validation');
    }
}
