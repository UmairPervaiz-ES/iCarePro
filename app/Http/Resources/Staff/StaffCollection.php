<?php

namespace App\Http\Resources\Staff;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StaffCollection extends ResourceCollection
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
            'user_key'         => $data->user_key,
            'first_name'       => $data->first_name,
            'middle_name'       => $data->middle_name,
            'last_name'       => $data->last_name,
            'email'       => $data->email,
            'department'       => $data->department_employee_type->department->only(['practice_id', 'name']),
            'employee_type'       => $data->department_employee_type->only(['department_id', 'practice_id', 'name']),
            'role'       => str_replace('practice-'.$data->practice_id.'@','', $data->roles->pluck('name')[0]),
            'role_id'       => $data->roles->pluck('id')[0],
            'is_active'       => $data->is_active,
            'created_at'       => Carbon::parse($data->created_at)->format('d-m-Y'),
            ];
        });
    }
}
