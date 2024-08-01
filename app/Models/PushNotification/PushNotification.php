<?php

namespace App\Models\PushNotification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PushNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function userPushNotifications(): HasMany
    {
        return $this->hasMany(UserPushNotification::class);
    }
}
