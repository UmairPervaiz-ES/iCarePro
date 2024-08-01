<?php

namespace App\Models\Subscription;

use App\Models\Practice\Practice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory ;
     protected $guarded = [];
    protected $guard_name = '';

    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }

    public function subscriptionTransections(){
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function subscriptionDiscount()
    {
        return $this->hasMany(SubscriptionDiscount::class);
    }

}
