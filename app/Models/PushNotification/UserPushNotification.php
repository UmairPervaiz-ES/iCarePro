<?php

namespace App\Models\PushNotification;

use App\Models\Doctor\Doctor;
use App\Models\Patient\Patient;
use App\Models\Practice\Practice;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPushNotification extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pushNotifications(): BelongsTo
    {
        return $this->belongsTo(PushNotification::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class,'user_id','id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class,'user_id','id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'user_id','id');
    }
}
