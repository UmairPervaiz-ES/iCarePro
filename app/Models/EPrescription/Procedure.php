<?php

namespace App\Models\EPrescription;

use App\Models\EPrescription;
use App\Traits\IdentifyAuthUser;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Procedure extends Model
{
    use HasFactory , SoftDeletes;
    use IdentifyCreateOrUpdate;
    // use IdentifyAuthUser;

    protected $guarded = [];

    public function prescribedProcedure(){
        return $this->hasOne(EPrescription\PrescribedProcedure::class);
    }
}
