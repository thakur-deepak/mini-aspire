<?php

namespace App\Repositories\Repayment;

use App\Models\Repayment;
use App\Repositories\Loan\LoanInterface;
use Illuminate\Database\Query\Builder;

class RepaymentRepository implements RepaymentInterface
{
    private Repayment $repayment;

    public function __construct(Repayment $repayment)
    {
        $this->repayment = $repayment;
    }

    public function create(array $attributes)
    {
        $total_amount = resolve(LoanInterface::class)->findBy('user_id', $attributes['user_id'])->amount;
        $attributes['total_amount_due'] = $total_amount - $attributes['amount_paid'];
        $last_payment = $this->findLatest('user_id', $attributes['user_id']);
        if ($last_payment) {
            $attributes['total_amount_due'] = $last_payment['total_amount_due'] - $attributes['amount_paid'];
        }
        return $this->repayment->create($attributes);
    }

    public function findLatest(string $column_name, string $value)
    {
        return $this->repayment->where($column_name, $value)->latest()->first();
    }

    public function findBy(string $column_name, string $value): ?Repayment
    {
        return $this->repayment->where($column_name, $value)->first();
    }

    public function find(int $id): ?Repayment
    {
        return $this->repayment->whereId($id)->first();
    }

    public function update(array $attributes): bool
    {
        return (bool) $this->repayment->whereUserId($attributes['user_id'])->update($attributes);
    }
}
