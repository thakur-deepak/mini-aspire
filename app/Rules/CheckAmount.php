<?php

namespace App\Rules;

use App\Repositories\Loan\LoanInterface;
use App\Repositories\Repayment\RepaymentInterface;
use Illuminate\Contracts\Validation\Rule;

class CheckAmount implements Rule
{
    private $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        $loan = resolve(LoanInterface::class)->find($this->id);
        $amount_due = resolve(RepaymentInterface::class)->findLatest('user_id', $this->id);
        if ($loan['amount'] >= $value && (!$amount_due || $amount_due['total_amount_due'] >= $value)) {
            return true;
        }

        return false;
    }

    public function message(): string
    {
        return trans('messages.validation.check_amount');
    }
}
