<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'course_id',
        'instructor_id',
        'student_id',
        'amount',
        'platform_commission',
        'instructor_commission',
        'payment_gateway_reference',
    ];
}
