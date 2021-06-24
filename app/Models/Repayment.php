<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_paid',
        'total_amount_due',
    ];

    public function getTotalAmountDueAttribute($value): string
    {
        return number_format((float)$value, 2, '.', '');
    }
}
