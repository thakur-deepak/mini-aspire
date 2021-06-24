<?php

namespace App\Repositories\Repayment;

use App\Models\Repayment;

interface RepaymentInterface
{
    public function create(array $attributes);

    public function findBy(string $column_name, string $value): ?Repayment;

    public function find(int $id): ?Repayment;

    public function update(array $attributes): bool;
}
