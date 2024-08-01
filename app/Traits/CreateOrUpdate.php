<?php

namespace App\Traits;
trait CreateOrUpdate
{

    /**
     * Description: Create or Update data
     * 1) If the register exists in the table, it updates it.
     * 2) Otherwise it creates it
     * @param array $data Data to Insert/Update
     * @param array $keys Keys to check for in the table
     * @return Object
     */
    public function createOrUpdate($model, $data, $keys)
    {
        $model = 'App\Models\\'. $model;
        $record = $model::where($keys)->first();
        if ($record) {
            $record->update($data);
            return $record;
        } else {
            return $model::create($data);
        }
    }
}
