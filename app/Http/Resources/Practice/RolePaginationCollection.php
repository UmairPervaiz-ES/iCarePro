<?php

namespace App\Http\Resources\Practice;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use JsonSerializable;

class RolePaginationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return $this->collection->transform(function($data) {
        return [
            'id'         => $data->id,
            'name'       => str_replace('practice-' . $this->practice_id() . '@','', $data->name),
            'created_at'       => $data->created_at,
            'updated_at'       => $data->updated_at,
            'permissions'         => $data->permissions,
        ];
    });
    }

    function practice_id()
    {
        $practiceID = Auth::getDefaultDriver();
        $practiceID ==  'practice-api' ? $practiceID = auth()->guard('practice-api')->id() :
        ($practiceID == 'doctor-api' ? $practiceID = auth()->guard('doctor-api')->user()->practice_id :
        ($practiceID == 'api' ? $practiceID = auth()->guard('api')->user()->practice_id : null ));
        return $practiceID;
    }
}
