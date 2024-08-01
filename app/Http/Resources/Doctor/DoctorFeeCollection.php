<?php

namespace App\Http\Resources\Doctor;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorFeeCollection extends ResourceCollection
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
            'amount' => $data->amount,
            'status'    => $data->status,
            'created_at'       => Carbon::parse($data->created_at)->format('d-m-Y'),
            'updated_at'    => Carbon::parse($data->updated_at)->format('d-m-Y'),
            ];
        });
    }
}
