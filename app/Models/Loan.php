<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
      'user_id',
      'amount',
      'loan_term',
      'is_approved'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'amount',
        'created_at',
        'updated_at'
    ];
}
