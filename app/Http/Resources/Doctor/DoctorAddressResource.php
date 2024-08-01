<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAddressResource extends JsonResource
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
            'current_country_id' => $this->currentCountry,
            'current_state_id' => $this->currentState,
            'current_city_id' => $this->currentCity,
            'home_town_country_id' => $this->homeTownCountry,
            'home_town_state_id' => $this->homeTownState,
            'home_town_city_id' => $this->homeTownCity,
            'current_address_1' => $this->current_address_1,
            'current_address_2' => $this->current_address_2,
            'current_zip_code' => $this->current_zip_code,
            'home_town_address_1' => $this->home_town_address_1,
            'home_town_address_2' => $this->home_town_address_2,
            'home_town_zip_code' => $this->home_town_zip_code,
        ];
    }
}
