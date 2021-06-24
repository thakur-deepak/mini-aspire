<?php

namespace App\Http\Requests;

use App\Rules\CheckAmount;
use App\Rules\Double;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Repayment extends FormRequest
{
    private $id;

    public function __construct(Request $request)
    {
        $this->id = $request->user()->id;
        $request->request->add(['user_id' => $this->id]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'amount_paid' => ['required', new Double(), new CheckAmount($this->id)]
        ];
    }

    public function messages(): array
    {
        return trans('messages.validation');
    }
}
