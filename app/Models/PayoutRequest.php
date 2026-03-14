<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'instructor_id',
        'amount',
        'status',
        'paid_at',
    ];
}
