<?php

namespace App\Models\Subscription;

use App\Models\Practice\Practice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    public function subscription()
    {
        return $this->belongsTo(Subscription::class , 'id', 'subscription_id');
    }

    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }
}
