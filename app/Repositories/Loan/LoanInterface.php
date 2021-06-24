<?php

namespace App\Repositories\Loan;

use App\Models\Loan;

interface LoanInterface
{
    public function create(array $data): Loan;

    public function findBy(string $column_name, string $value): ?Loan;

    public function find(int $id): ?Loan;

    public function checkUser(int $id): bool;

    public function update(array $attributes): bool;
}
