<?php

namespace App\Models\ConsentForm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsentFormLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];  
    
    public function consentForm(): HasOne
    {
        return $this->hasOne(ConsentForm::class);
    }
}
