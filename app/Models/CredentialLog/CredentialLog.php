<?php

namespace App\Models\CredentialLog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredentialLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
}
