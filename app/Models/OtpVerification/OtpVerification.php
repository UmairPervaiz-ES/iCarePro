<?php

namespace App\Models\OtpVerification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtpVerification extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
}