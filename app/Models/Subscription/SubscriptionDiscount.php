<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDiscount extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class);
    }
}
