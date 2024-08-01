<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorSlotCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($data) {
            return [
                'id' => $data->id,
                'date_from' => $data->date_from,
                'date_to'   => $data->date_to,
                'time_from' => $data->time_from,
                'time_to'   => $data->time_to,
                'slot_time' => $data->slot_time,
                'status'    => $data->status,
                'days'      => new DoctorSlotDayCollection($data->doctorSlotDays)
            ];
        });
    }
}
