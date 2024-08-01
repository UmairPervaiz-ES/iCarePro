<?php

namespace App\Models\Practice;

use App\Models\ActivityLog\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InitialPractice extends Model
{

    use HasFactory;
    protected $table = 'practice_registration_requests';
    protected $guarded = [];

    public function practice(){
        return $this->hasOne(Practice::class);
    }
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function practiceContacts()
    {
        return $this->hasMany(PracticeContact::class);
    }

    public function practiceRequest(): HasOne
    {
        return $this->hasOne(Practice::class, 'practice_registration_request_id', 'id');
    }
}
