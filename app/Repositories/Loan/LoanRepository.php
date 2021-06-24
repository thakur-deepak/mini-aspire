<?php

namespace App\Repositories\Loan;

use App\Models\Loan;

class LoanRepository implements LoanInterface
{
    private $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function create(array $attributes): Loan
    {
        return $this->loan->create($attributes);
    }

    public function findBy(string $column_name, string $value): ?Loan
    {
        return $this->loan->where($column_name, $value)->first();
    }

    public function find(int $id): ?Loan
    {
        return $this->loan->whereId($id)->first();
    }

    public function checkUser(int $id): bool
    {
        return $this->loan->whereUserId($id)->exists();
    }

    public function update(array $attributes): bool
    {
        return (bool) $this->loan->whereUserId($attributes['user_id'])->update($attributes);
    }
}
