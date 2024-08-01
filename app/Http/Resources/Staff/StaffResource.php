<?php

namespace App\Http\Resources\Staff;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'first_name'       => $this->first_name,
            'middle_name'       => $this->middle_name,
            'last_name'       => $this->last_name,
            'email'       => $this->email,
            'credentials_send_at'       => $this->credentials_send_at,
            'secondary_email'       => $this->secondary_email,
            'country_code_phone_number'       => $this->country_code_phone_number,
            'phone_number'       => $this->phone_number,
            'country_code_secondary_phone_number'       => $this->country_code_secondary_phone_number,
            'secondary_phone_number'       => $this->secondary_phone_number,
            'gender'       => $this->gender,
            'dob'       => $this->dob,
            'department_id'       => $this->department_id,
            'department_employee_type_id'       => $this->department_employee_type_id,
            'current_country'       => $this->currentCountry['name'],
            'current_state'       => $this->currentState['name'],
            'current_city'       => $this->currentCity['name'],
            'current_country_id'       => $this->current_country_id,
            'current_state_id'       => $this->current_state_id,
            'current_city_id'       => $this->current_city_id,
            'current_address_1'       => $this->current_address_1,
            'current_address_2'       => $this->current_address_2,
            'current_zip_code'       => $this->current_zip_code,
            'home_country'       => $this->homeTownCountry['name'],
            'home_state'       => $this->homeTownState['name'],
            'home_city'       => $this->homeTownCity['name'],
            'home_town_country_id'       => $this->home_town_country_id,
            'home_town_state_id'       => $this->home_town_state_id,
            'home_town_city_id'       => $this->home_town_city_id,
            'home_address_1'       => $this->home_address_1,
            'home_address_2'       => $this->home_address_2,
            'home_zip_code'       => $this->home_zip_code,
            'profile_photo_url'       => $this->profile_photo_url,
            'is_password_reset'       => $this->is_password_reset,
            'is_first_login'       => $this->is_first_login,
            'registration'       => $this->registration,
            'is_active'       => $this->is_active,
            'department'       => $this->department_employee_type->department->only(['practice_id', 'name']),
            'employee_type'       => $this->department_employee_type->only(['department_id', 'practice_id', 'name']),
            'role'       => str_replace('practice-'.$this->practice_id.'@','', $this->roles->pluck('name')[0]),
            'role_id'       => $this->roles->pluck('id')[0],
            'status'       => $this->status,
            'created_at'       => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];
    }
}
