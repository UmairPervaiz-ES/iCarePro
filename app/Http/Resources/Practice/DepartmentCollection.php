<?php

namespace App\Http\Resources\Practice;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentCollection extends ResourceCollection
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
            'name'       => $data->name,
            'employee_type'     => $data->departmentEmployeeTypes,
            ];
        });
    }
}
