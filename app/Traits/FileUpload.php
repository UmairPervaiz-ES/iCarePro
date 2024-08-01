<?php

namespace App\Traits;
trait FileUpload
{

    /**
     * Description: Create or Update data
     * 1) If the register exists in the table, it updates it.
     * 2) Otherwise it creates it
     * @param array $data Data to Insert/Update
     * @param array $keys Keys to check for in the table
     * @return Object
     */
    public function fileUpload($request, $name, $path)
    {
       if($request->hasFile($name)){
        $extension = $request->file($name)->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $name.'_' .time() . '.' . $extension;
        // Upload Image
        $request->file($name)->storeAs('public/'.$path, $fileNameToStore);
        return $fileNameToStore;
        }
        
    }
}
