<?php

namespace App\Models\EPrescription;

use App\Traits\IdentifyAuthUser;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTest extends Model
{
    use HasFactory , SoftDeletes;
    use IdentifyCreateOrUpdate;
    // use IdentifyAuthUser;

    protected $guarded = [];

    public function prescribedLabTest(){
        return $this->hasOne(PrescribedLabTest::class);
    }

}