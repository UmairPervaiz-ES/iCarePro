<?php

namespace App\Http\Resources\Doctor;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PracticeRequestResource extends JsonResource
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
            'id' => $this->id,
            'practice_id' => $this->practice_id,
            'status' => $this->status,
            'count' => $this->count,
            'created_at' => $this->created_at,
            'practice' => $this->practice,
        ];
    }
}
