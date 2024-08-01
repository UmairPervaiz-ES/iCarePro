<?php

namespace App\Http\Resources\Practice;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PracticeListCollection extends ResourceCollection
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
            'id'         => $data->id,
            'practice_registration_request_id'       => $data->practice_registration_request_id,
            'name'     => $data->initialPractice->practice_name,
            ];
        });
    }
}
