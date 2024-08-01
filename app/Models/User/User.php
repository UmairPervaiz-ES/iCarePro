<?php

namespace App\Models\User;

use App\Models\City\City;
use App\Models\Country\Country;
use App\Models\Department\Department;
use App\Models\Department\DepartmentEmployeeType;
use App\Models\Practice\Practice;
use App\Models\PushNotification\UserPushNotification;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guard_name = 'api';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function department_employee_type(): BelongsTo
    {
        return $this->belongsTo(DepartmentEmployeeType::class, 'department_employee_type_id', 'id');
    }

    public function currentCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'current_country_id', 'id');
    }

    public function currentState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'current_state_id', 'id');
    }

    public function currentCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'current_city_id', 'id');
    }

    public function homeTownCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'home_town_country_id', 'id');
    }

    public function homeTownState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'home_town_state_id', 'id');
    }

    public function homeTownCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'home_town_city_id', 'id');
    }

    public function userPushNotifications(): HasMany
    {
        return $this->hasMany(UserPushNotification::class,'user_id','id')->where('guard_name','=','api');
    }
}
